<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhCase extends Model
{
    //table
    protected $table = 'cases';

    //getter to cleanup content
    public function getContentAttribute($value)
    {
        return $this->cleanContent($value);
    }

    public function cleanContent($content)
    {
        return str_replace("\u{FFFD}", '', $content); // Remove the replacement character
    }

}
