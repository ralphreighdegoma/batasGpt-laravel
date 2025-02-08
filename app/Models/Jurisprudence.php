<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurisprudence extends Model
{
    protected $fillable = [
        'title',
        'content',
        'reference_number',
        'decision_date',
        'court',
    ];
}
