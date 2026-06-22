<?php

namespace App\Events;

use App\Models\Kemitraan;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KemitraanStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $kemitraan;

    public function __construct(Kemitraan $kemitraan)
    {
        $this->kemitraan = $kemitraan;
    }

    public function broadcastOn(): array
    {
        // Broadcast ke channel khusus user tersebut
        return [
            new PrivateChannel('kemitraan.' . $this->kemitraan->userId),
            new PrivateChannel('admin.kemitraan') 
        ];
    }
}
