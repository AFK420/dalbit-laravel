<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class QrScan extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'source',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];
}
