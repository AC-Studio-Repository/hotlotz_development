<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Item\Models\Item;

class ItemLifecycleStartEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $itemlifecycle_id, $item_id, $itemlifecycle, $lifecycle_status, $email_data;
    
    public function __construct($itemlifecycle_id, $item_id, $itemlifecycle, $lifecycle_status, $email_data)
    {
        $this->itemlifecycle_id = $itemlifecycle_id;
        $this->item_id = $item_id;
        $this->itemlifecycle = $itemlifecycle;
        $this->lifecycle_status = $lifecycle_status;
        $this->email_data = $email_data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
