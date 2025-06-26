<?php

namespace App\Http\Controllers;

use App\Events\GameEnded;
use App\Events\PlayerJoined;
use App\Events\GameStarted;
use App\Events\QuestionChanged;
use App\Models\GameSession;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GameSessionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "quiz_id" => "required|exists:quizzes,id",
        ]);

        $quiz = Quiz::findOrFail($request->quiz_id);

        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $sessionCode = Str::upper(Str::random(6));

        $gameSession = GameSession::create([
            "quiz_id" => $quiz->id,
            "session_code" => $sessionCode,
            "status" => "waiting",
            "current_question_index" => 0,
        ]);

        return response()->json($gameSession, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GameSession $gameSession)
    {
        if ($gameSession->quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }
        $gameSession->load("quiz.questions.questionType");
        return response()->json($gameSession);
    }

    /**
     * Start the specified game session.
     */
    public function start(GameSession $gameSession)
    {
        if ($gameSession->quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        if ($gameSession->status !== "waiting") {
            return response()->json(["message" => "Game session is not in waiting status."], 400);
        }

        $gameSession->update(['status' => 'in_progress']);
        broadcast(new GameStarted($gameSession->session_code));

        return response()->json($gameSession);
    }

    /**
     * Enters the player into the session.
     */
    public function join(GameSession $gameSession)
    {
        $request = request();

        $request->validate([
            'session_code' => 'required|exists:game_sessions,session_code',
            'name' => 'required|string|max:50'
        ]);

        // Check if game hasn't started
        if ($gameSession->status !== 'waiting') {
            return response()->json([
                'message' => 'Game has already started'
            ], 400);
        }

        // Create player
        $player = $gameSession->players()->create([
            'name' => $request->name,
            'score' => 0
        ]);

        $player->load('gameSession');

        // Broadcast join event
        broadcast(new PlayerJoined($gameSession, $request->name));

        return response()->json($player, 201);
    }

    public function nextQuestion(GameSession $gameSession)
    {
        $totalQuestions = $gameSession->quiz->questions->count();

        if ($gameSession->current_question_index < $totalQuestions - 1) {
            broadcast(new QuestionChanged($gameSession));
        } else {
            $gameSession->status = "finished";
            $gameSession->save();

            broadcast(new GameEnded($gameSession));
        }

        return response()->json($gameSession);
    }

    /**
     * End the specified game session.
     */
    public function end(GameSession $gameSession)
    {
        if ($gameSession->quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }
        
        $gameSession->load("players");
        $gameSession->status = "finished";
        $gameSession->save();

        // Broadcast session ended event with results
        broadcast(new GameEnded($gameSession));

        return response()->json($gameSession);
    }
}


