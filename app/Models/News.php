<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['title', 'body', 'image_path', 'video_path', 'photos', 'videos', 'category', 'is_breaking', 'published_at'];

    protected $casts = [
        'is_breaking' => 'boolean',
        'published_at' => 'datetime',
        'photos' => 'array',
        'videos' => 'array',
    ];
}
