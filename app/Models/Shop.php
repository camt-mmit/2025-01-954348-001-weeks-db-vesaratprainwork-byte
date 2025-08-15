<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['code', 'name', 'owner', 'latitude', 'longitude', 'address'];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}
