<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $isDelete;
    public $isReadUpdate;

    public function __construct($chat, $isDelete = false, $isReadUpdate = false)
    {
        $this->chat = $chat;
        $this->isDelete = $isDelete;
        $this->isReadUpdate = $isReadUpdate;
    }

    public function broadcastOn(): array
    {
        $penerima = is_array($this->chat) ? $this->chat['id_penerima'] : $this->chat->id_penerima;
        if ($penerima === 'GLOBAL') return [new Channel('chat.global')];
        return [new PrivateChannel('chat.' . $penerima)];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => $this->chat,
            'is_delete' => $this->isDelete,
            'is_read_update' => $this->isReadUpdate
        ];
    }
}
