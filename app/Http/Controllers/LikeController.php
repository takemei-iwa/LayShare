<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Like;
use App\Models\Layout;

class LikeController extends Controller
{
    public function store(Layout $layout) {
        $input = [
            'user_id' => Auth::user()->id,
            'layout_id' => $layout->id,
        ];
        $like = new Like();
        $like->fill($input)->save();
    }
    public function destroy(Layout $layout) {
        $like = new Like();
        $like->where([
            ['user_id', '=', Auth::user()->id],
            ['layout_id', '=', $layout->id],
        ])->delete();
    }
}
