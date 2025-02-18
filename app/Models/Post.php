<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'images',
        'audio',
        'tags'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes');
    }
    //images is an array of strings
    public function getImagesAttribute($value)
    {
        $images = is_string($value) ? json_decode($value, true) : $value;
        if (!$images) {
            return [];
        }
        return array_map(function($image) {
            return url('/storage/' . $image);
        }, $images);
    }

    public function getTagsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAudioAttribute($value)
    {
        if ($value) {
            return url('/storage/' . $value);
        }
        return null;
    }

}