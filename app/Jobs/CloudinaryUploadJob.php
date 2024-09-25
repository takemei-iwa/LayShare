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
    /**
     * Create a new job instance.
     */
    public function __construct($html=null,$css=null,$imageDataURL, $layout)
    {
        // 一時的に保存するディレクトリのパス        
        $this->html=$html;        
        // $this->css=$css;        
        $this->imageDataURL=$imageDataURL;
        $this->layout = $layout;
    }

    private function uploadFile($fileName, $file, $storage_path){
        Storage::disk('public')->put('tmp/' . $fileName, $file);
        $uploadedFileUrl = Cloudinary::uploadFile($storage_path . '/' . $fileName)->getSecurePath();
        return $uploadedFileUrl;
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
        $uploadedImageUrl = Cloudinary::upload($storage_path . '/' . $imgName)->getSecurePath();

        return $uploadedImageUrl;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug("Called CloudinaryUploadJob");
        $storage_path = Storage::disk('public')->path('tmp');
        if ($this->html !== null) {
            $htmlName = uniqid() . '.html';
            $uploadedHtmlUrl = $this->uploadFile($htmlName, $this->html, $storage_path);
        } 
        Log::debug("uploadhtml : {$uploadedHtmlUrl}");
        // if ($css !== null) {
        //     $cssName = uniqid() . '.css';
        //     $uploadedCssUrl = $this->uploadFile($cssName, $this->css, $storage_path);
        // }
        // $uploadedImageUrl = $this->uploadImage($this->imageDataUrl, $storage_path);
        // // uploadしたurlを返す
        // $input = [
        //     'html' => $uploadedHtmlUrl,
        //     'css' => $uploadedCssUrl,
        //     'thumbnail' => $uploadedImageUrl,
        //     'user_id' => Auth::user()->id,
        // ];        
        // $this->layout->fill($input)->save();
    }
}
