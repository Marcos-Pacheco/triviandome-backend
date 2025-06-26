<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $question;
    public $timeLimit;

    public function __construct(GameSession $session)
    {
        $this->session = $session;
        $this->question = $session->quiz->questions[$session->current_question_index];
        $this->timeLimit = $this->question->time_limit;
    }

    public function broadcastOn()
    {
        return new Channel('game.' . $this->session->session_code);
    }

    public function broadcastWith()
    {
        return [
            'question' => [
                'id' => $this->question->id,
                'text' => $this->question->text,
                'options' => $this->question->options,
                'time_limit' => $this->timeLimit
            ],
            'current_question_index' => $this->session->current_question_index,
            'total_questions' => $this->session->quiz->questions->count()
        ];
    }
}