<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Like;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'html',
        'css',
        'thumbnail',
        'user_id',
    ];
    
    public function likes()   
    {
        return $this->hasMany(Like::class);  
    }
}
