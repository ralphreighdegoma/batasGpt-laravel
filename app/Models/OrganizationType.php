<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
