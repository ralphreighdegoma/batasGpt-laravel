<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'mobile_number',
        'work_number',
        'organisation_name',
        'search_address',
        'suburb',
        'region',
        'postcode',
        'state',
        'is_indigenous_organisation',
    ];

    public function createProfile(array $data)
    {
        return $this->create($data);
    }
}
