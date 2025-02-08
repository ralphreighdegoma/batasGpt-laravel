<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically set the user_id when creating a note
        static::creating(function ($note) {
            $note->user_id = Auth::id(); // Set the current user's ID
        });
    }
}
