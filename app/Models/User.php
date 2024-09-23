<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Like;
use App\Models\Layout;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function likes()   
    {
        return $this->hasMany(Like::class);  
    }

    public function likedLayouts()
    {   
        $likes = Like::whereBelongsTo($this)->get();
        $layouts = [];
        foreach($likes as $like) {
            // echo "<pre>";
            // var_dump($like->layout()->first()->id);
            // echo "</pre>";
            $layouts[] = $like->layout()->first();
        }
        // $layouts = $this->belongsToMany(Layout::class)->using(Like::class);
        // dd($layouts);
        return $layouts;
        
        // return $this->belongsToMany(Layout::class, 'likes', 'user_id', 'layout_id');
    }
}
