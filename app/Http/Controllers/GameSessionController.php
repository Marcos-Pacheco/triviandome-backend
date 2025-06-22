<?php

namespace App\Http\Controllers;

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

        $gameSession->status = "in_progress";
        $gameSession->save();

        // TODO: Broadcast event to connected players

        return response()->json($gameSession);
    }

    /**
     * Move to the next question in the specified game session.
     */
    public function nextQuestion(GameSession $gameSession)
    {
        if ($gameSession->quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        if ($gameSession->status !== "in_progress") {
            return response()->json(["message" => "Game session is not in progress."], 400);
        }

        $totalQuestions = $gameSession->quiz->questions->count();

        if ($gameSession->current_question_index < $totalQuestions - 1) {
            $gameSession->current_question_index++;
            $gameSession->save();
            // TODO: Broadcast event to connected players with new question
        } else {
            $gameSession->status = "finished";
            $gameSession->save();
            // TODO: Broadcast event to connected players with results
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

        $gameSession->status = "finished";
        $gameSession->save();

        // TODO: Broadcast event to connected players with final results

        return response()->json($gameSession);
    }
}


