<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameEnded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $results;

    public function __construct(GameSession $session)
    {
        $this->session = $session;
        $this->results = $this->prepareResults($session);
    }

    public function broadcastOn()
    {
        return new Channel('game.' . $this->session->session_code);
    }

    public function broadcastAs()
    {
        return 'session.ended';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Game session has ended',
            'results' => $this->results
        ];
    }

    protected function prepareResults(GameSession $session)
    {
        return $session->players()
            ->orderByDesc('score')
            ->get(['name', 'score'])
            ->toArray();
    }
}