<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function adminLoginLogs(): HasMany
    {
        return $this->hasMany(AdminLoginLog::class);
    }

    public function handledOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'handled_by_admin_id');
    }
}
