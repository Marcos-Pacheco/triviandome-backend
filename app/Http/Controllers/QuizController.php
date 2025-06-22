<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Auth::user()->quizzes()->with("questions")->get();
        return response()->json($quizzes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "is_public" => "boolean",
        ]);

        $quiz = Auth::user()->quizzes()->create($request->all());

        return response()->json($quiz, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }
        $quiz->load("questions.questionType");
        return response()->json($quiz);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "is_public" => "boolean",
        ]);

        $quiz->update($request->all());

        return response()->json($quiz);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $quiz->delete();

        return response()->json(null, 204);
    }
}


