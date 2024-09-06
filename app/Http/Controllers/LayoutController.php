<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Layout;

use Cloudinary;

class LayoutController extends Controller
{   
    public function index()
    {
        return view('layouts.index');
    }
    public function create()
    {
        return view('layouts.create');
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
    public function store(Request $request)
    {   
        // $input = $request['layout'];
        // dump($input);
        // // $imageData = $input["img"];
        // $imageData = str_replace('data:image/png;base64,', '', $imageData);
        // $imageData = str_replace(' ', '+', $imageData);
        // $image = base64_decode($imageData);

        // $imagePath = public_path('test_files/' . uniqid() . '.png');
        // $htmlPath = public_path('test_files/' . uniqid() . '.html');
        // $cssPath = public_path('test_files/' . uniqid() . '.css');        
        
        // // ディレクトリが存在しない場合は作成
        // $directory = dirname($imagePath);
        // if (!File::exists($directory)) {
        //     File::makeDirectory($directory, 0755, true);
        // }

        // $html =  $request->input('html');
        // $css =  $request->input('css');

        // dump($html);
        // dump($css);
        // dump($imageData == null);

        // File::put($imagePath, $image);
        // File::put($htmlPath, $html);
        // File::put($cssPath, $css);
        // return response()->json(['message' => 'request getted successfully']);

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
            $cssURL = $this->uploadFile($cssName, $css, $storage_path);
        }
        $imageDataUrl = $request->input('image');
        $uploadedImageUrl = $this->uploadImage($imageDataUrl, $storage_path);
        
        // URLからダウンロードできるか確認
        // $htmlGot = file_get_contents($uploadedHtmlUrl);
        // dd($htmlGot);
        // dd($uploadedHtmlUrl . '\n' . 
        //     $uploadedCssUrl . '\n' . 
        //     $uploadedImageUrl);

        // uploadしたurlを返す
        return response()->json([
            'html' => $uploadedHtmlUrl,
            'css' => $uploadedCssUrl,
            'image' => $uploadedImageUrl
        ]);
    }
}
