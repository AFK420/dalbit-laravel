<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PendingConfirmation = 'pending_confirmation';
    case New = 'new';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
