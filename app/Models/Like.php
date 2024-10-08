<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Layout;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'layout_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function layout() {
        return $this->belongsTo(Layout::class);
    }
    
    public static function isLiked(Layout $layout) {        
        // dd(Auth::user()->id . " " . $layout->id);
        if(!Auth::user()) { 
            return false;
        }
        return Like::where([
            ['user_id', '=', Auth::user()->id],
            ['layout_id', '=', $layout->id],
        ])->exists();
    }
    public static function getLikesCount(Layout $layout) {        
        // dd(Auth::user()->id . " " . $layout->id);        
        return Like::where([
            ['layout_id', '=', $layout->id],
        ])->count();
    }
}
