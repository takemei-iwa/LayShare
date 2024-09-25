<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\Layout;
use App\Models\Like;
use App\Models\User;

use App\Jobs\CloudinaryUploadJob;

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
    public function getLikedLayouts(){
        return Inertia::render('Layouts/LikedLayouts', [
            "layouts" => Auth::user()->likedLayouts(),
        ]);
    }
    public function getUserLayouts(){
        return Inertia::render('Layouts/UserLayouts', [
            "layouts" => Auth::user()->layouts()->get(),
        ]);
    }
    private function save(Request $request, Layout $layout)
    {
        // フォームからのデータを取得
        $html = $request->input('html');
        $css = $request->input('css');
        $imageDataUrl = $request->input('image');
        // ジョブをディスパッチしてバックグラウンド処理を実行
        CloudinaryUploadJob::dispatch($html, $css, $imageDataUrl, $layout, Auth::user()->id, );
    }
    public function store(Request $request, Layout $layout)
    {   
        $isCreate = false;
        $this->save($request, $layout, $isCreate);
        
        return redirect()->back()->with('message', 'Data upload started. You will be notified once complete.');
    }
}
