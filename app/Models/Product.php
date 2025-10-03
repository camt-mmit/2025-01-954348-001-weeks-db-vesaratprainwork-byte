<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'price', 'description'];

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class)->withTimestamps();;
    }
}