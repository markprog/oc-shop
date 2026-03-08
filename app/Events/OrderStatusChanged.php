<?php

namespace App\Events;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order       $order,
        public readonly OrderStatus $newStatus,
        public readonly bool        $notify = false,
        public readonly string      $comment = '',
    ) {}
}
