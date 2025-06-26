<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;

class QuestionStarted implements ShouldBroadcast
{
    public $sessionCode;
    public $question;

    public function __construct($sessionCode, $question)
    {
        $this->sessionCode = $sessionCode;
        $this->question = $question;
    }

    public function broadcastOn()
    {
        return new Channel('game.' . $this->sessionCode);
    }
}