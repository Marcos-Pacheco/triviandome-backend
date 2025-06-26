<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerJoined implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $playerName;
    public $playerCount;

    public function __construct(GameSession $session, string $playerName)
    {
        $this->session = $session;
        $this->playerName = $playerName;
        $this->playerCount = $session->players()->count();
    }

    public function broadcastOn()
    {
        return new Channel('game.' . $this->session->session_code);
    }

    public function broadcastAs()
    {
        return 'player.joined';
    }

    public function broadcastWith()
    {
        return [
            'player_name' => $this->playerName,
            'player_count' => $this->playerCount
        ];
    }
}