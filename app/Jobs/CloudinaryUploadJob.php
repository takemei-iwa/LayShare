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

use App\Events\UploadCompleted;

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

    private function deleteFile($path){
        if (Storage::disk('public')->exists($path)) {
            Log::debug("delete file path : " . $path);
            Storage::disk('public')->delete($path);            
        } else {
            Log::error("Error : uploadedFile doesnt exist. path : " . $path);
        }
    }
    private function uploadFile($fileName, $file, $storage_path){
        Storage::disk('public')->put('tmp/' . $fileName, $file);
        $path = $storage_path . '/' . $fileName;
        $uploadedFileURL = Cloudinary::uploadFile($path)->getSecurePath();
        $this->deleteFile('tmp/' . $fileName);
        return $uploadedFileURL;
    }
    private function uploadImage($imageDataURL, $storage_path){
        $uploadedImageURL = Cloudinary::upload($imageDataURL)->getSecurePath();
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
        } else {
            $this->layout->fill($input)->save();
        }
        Log::debug("Complete CloudinaryUploadJob");
        event(new UploadCompleted($this->user_id));
    }
}
