<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }
        return response()->json($quiz->questions()->with("questionType")->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $request->validate([
            "question_type_id" => "required|exists:question_types,id",
            "text" => "required|string",
            "options" => "nullable|array",
            "correct_answer" => "nullable|string",
            "points" => "integer|min:0",
            "time_limit" => "integer|min:5",
        ]);

        $question = $quiz->questions()->create($request->all());

        return response()->json($question, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question)
    {
        if ($quiz->user_id !== Auth::id() || $question->quiz_id !== $quiz->id) {
            return response()->json(["message" => "Unauthorized"], 403);
        }
        $question->load("questionType");
        return response()->json($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        if ($quiz->user_id !== Auth::id() || $question->quiz_id !== $quiz->id) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $request->validate([
            "question_type_id" => "required|exists:question_types,id",
            "text" => "required|string",
            "options" => "nullable|array",
            "correct_answer" => "nullable|string",
            "points" => "integer|min:0",
            "time_limit" => "integer|min:5",
        ]);

        $question->update($request->all());

        return response()->json($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        if ($quiz->user_id !== Auth::id() || $question->quiz_id !== $quiz->id) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $question->delete();

        return response()->json(null, 204);
    }
}


