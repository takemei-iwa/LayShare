<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Layout;


use Cloudinary;

class CloudinaryUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $imageDataURL;
    private $html, $css;
    private $layout;
    private $user_id;
    private $isStore;
    /**
     * Create a new job instance.
     */
    public function __construct($html=null,$css=null,$imageDataURL, ?Layout $layout, $user_id, $isStore)
    {
        // 一時的に保存するディレクトリのパス        
        $this->html=$html;        
        $this->css=$css;        
        $this->imageDataURL=$imageDataURL;
        $this->user_id = $user_id;
        $this->isStore = $isStore;
        if(!$this->isStore){
            $this->layout = $layout;
        }
    }

    private function uploadFile($fileName, $file, $storage_path){
        Storage::disk('public')->put('tmp/' . $fileName, $file);
        $uploadedFileURL = Cloudinary::uploadFile($storage_path . '/' . $fileName)->getSecurePath();
        return $uploadedFileURL;
    }
    private function uploadImage($imageDataURL, $storage_path){
        $parts = explode(',', $imageDataURL);
        $data = isset($parts[1]) ? $parts[1] : null;

        if ($data === null) {
            return response()->json(['error' => 'No image data provided'], 400);
        }

        // Base64デコード
        $data = base64_decode($data);

        // ファイル名を生成
        $imgName = uniqid() . '.png'; 

        // 画像をストレージに保存
        Storage::disk('public')->put('tmp/' . $imgName, $data);
        $uploadedImageURL = Cloudinary::upload($storage_path . '/' . $imgName)->getSecurePath();

        return $uploadedImageURL;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug("Called CloudinaryUploadJob. isStore : {$this->isStore}");
        $storage_path = Storage::disk('public')->path('tmp');
        $uploadedHtmlURL="";
        if ($this->html !== null) {
            $htmlName = uniqid() . '.html';
            $uploadedHtmlURL = $this->uploadFile($htmlName, $this->html, $storage_path);
        } 
        
        $uploadedCssURL="";
        if ($this->css !== null) {
            $cssName = uniqid() . '.css';
            $uploadedCssURL = $this->uploadFile($cssName, $this->css, $storage_path);
        }
        
        $uploadedImageURL = $this->uploadImage($this->imageDataURL, $storage_path);
        
        // uploadしたuRLを返す
        $input = [
            'html' => $uploadedHtmlURL,
            'css' => $uploadedCssURL,
            'thumbnail' => $uploadedImageURL,
            'user_id' => $this->user_id,
        ];
        if($this->isStore){
            Layout::create($input);
            Log::debug("layout create comment");
        } else {
            Log::debug("layout fill comment");
            $this->layout->fill($input)->save();
        }
        Log::debug("complete upload");
    }
}
