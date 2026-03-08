<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductReviewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Review $review)
    {
    }
}
