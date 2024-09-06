<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Layout;

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

        // フォームからのデータを取得
        $html = $request->input('html');
        if ($html === null) {
            $html = "";
        }
        $css = $request->input('css');
        if ($css === null) {
            $css = "";
        }
        

        $htmlName = uniqid() . '.html';
        $cssName = uniqid() . '.css';
        
        Storage::disk('public')->put('tmp/' . $htmlName, $html);
        Storage::disk('public')->put('tmp/' . $cssName, $css);

        $dataURL = $request->input('image');
        $parts = explode(',', $dataURL);
        $data = isset($parts[1]) ? $parts[1] : null;

        if ($data === null) {
            return response()->json(['error' => 'No image data provided'], 400);
        }

        // Base64デコード
        $data = base64_decode($data);

        // ファイル名を生成
        $imgName = uniqid() . '.png'; // PNGファイルとして保存

        // 画像をストレージに保存
        Storage::disk('public')->put('tmp/' . $imgName, $data);

        // 保存した画像のURLを取得
        $imageUrl = Storage::url('tmp/' . $imgName);


        // 画像のURLをレスポンスとして返す
        return response()->json(['url' => $imageUrl]);
        // データを使って何かをする
        // ここでは、受信したデータを表示するだけです
        return "受信したメッセージ: " . htmlspecialchars($image) . " " . htmlspecialchars($css);
    }
}
