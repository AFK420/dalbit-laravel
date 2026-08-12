<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'name_ar',
        'category',
        'category_ar',
        'type',
        'price',
        'currency',
        'image_path',
        'is_visible',
        'fallback_placeholder',
        'short_description',
        'short_description_ar',
        'full_description',
        'full_description_ar',
        'flavor_profile',
        'flavor_profile_ar',
        'allergens',
        'allergens_ar',
        'highlights',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'price' => 'decimal:2',
        'flavor_profile' => 'array',
        'flavor_profile_ar' => 'array',
        'allergens' => 'array',
        'allergens_ar' => 'array',
        'highlights' => 'array',
    ];
}
