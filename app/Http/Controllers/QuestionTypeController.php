<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(QuestionType::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionType $questionType)
    {
        return response()->json($questionType);
    }
}


