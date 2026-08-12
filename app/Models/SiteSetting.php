<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'show_marquee',
        'hero_image',
        'story_image',
        'stickers',
        'updated_at',
    ];

    protected $casts = [
        'show_marquee' => 'boolean',
        'stickers' => 'array',
        'updated_at' => 'datetime',
    ];
}
