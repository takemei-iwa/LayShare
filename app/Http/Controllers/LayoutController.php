<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\Layout;
use App\Models\Like;

use Cloudinary;

class LayoutController extends Controller
{   
    public function index(Layout $layout)
    {
        return Inertia::render('Layouts/Index', ["layouts" => $layout->get()]);
    }
    public function create()
    {
        return Inertia::render('Layouts/Create');
    }
    public function edit(Layout $layout)
    {   
        $layoutData = $layout;
        if($layoutData->html !== ""){
            $layoutData->html = file_get_contents($layout->html);
        }
        if($layoutData->css !== ""){
            $layoutData->css = file_get_contents($layout->css);
        }
        return Inertia::render('Layouts/Edit', [
            "layout" => $layoutData,
            "isLiked" => Like::isLiked($layout),
            "initialLikesCount" => Like::getLikesCount($layout),
        ]);
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
    public function store(Request $request, Layout $layout)
    {   
        // 一時的に保存するディレクトリのパス
        $storage_path = Storage::disk('public')->path('tmp');
        // フォームからのデータを取得
        $html = $request->input('html');
        $uploadedHtmlUrl = "";
        if ($html !== null) {
            $htmlName = uniqid() . '.html';
            $uploadedHtmlUrl = $this->uploadFile($htmlName, $html, $storage_path);
        }

        $css = $request->input('css');
        $uploadedCssUrl = "";
        if ($css !== null) {
            $cssName = uniqid() . '.css';
            $uploadedCssUrl = $this->uploadFile($cssName, $css, $storage_path);
        }
        $imageDataUrl = $request->input('image');
        $uploadedImageUrl = $this->uploadImage($imageDataUrl, $storage_path);
        
        // uploadしたurlを返す
        $input = [
            'html' => $uploadedHtmlUrl,
            'css' => $uploadedCssUrl,
            'thumbnail' => $uploadedImageUrl,
            'user_id' => Auth::user()->id,
        ];
        $layout->fill($input)->save();
        return response()->json($input);
    }
}
