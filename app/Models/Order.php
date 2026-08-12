<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_name',
        'phone',
        'location',
        'gift_note',
        'special_instructions',
        'deletion_reason',
        'items',
        'total_amount',
        'status',
        'ip_address',
        'delivery_date',
        'delivery_slot',
        'handled_by_admin_id',
        'handled_at',
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'status' => OrderStatus::class,
        'delivery_date' => 'date',
        'handled_at' => 'datetime',
    ];

    public function handledByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'handled_by_admin_id');
    }

    public function customerFeedback(): HasOne
    {
        return $this->hasOne(CustomerFeedback::class);
    }
}
