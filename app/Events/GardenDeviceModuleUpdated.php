<?php

namespace App\Events;

use App\Utils\LogUtil;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GardenDeviceModuleUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels, LogUtil;

    public $module;
    public $userId;

    public function __construct($module, $userId)
    {
        $this->module = $module;
        $this->userId = $userId;
    }

    public function broadcastAs()
    {
        return 'GardenDeviceModuleUpdated';
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'module' => $this->module
        ];
    }
}
