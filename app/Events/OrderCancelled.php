<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('items.product');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('store.' . $this->order->store_id . '.queue'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'order' => $this->order->toArray(),
        ];
    }
}
