<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLoginLog extends Model
{
    use HasUuids;

    public $timestamps = false; // Using custom logged_in_at

    protected $fillable = [
        'admin_id',
        'logged_in_at',
        'ip_address',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
