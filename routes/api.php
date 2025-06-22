<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\GameSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::get("question-types", [QuestionTypeController::class, "index"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/user", [AuthController::class, "user"]);
    Route::apiResource("quizzes", QuizController::class);
    Route::apiResource("quizzes.questions", QuestionController::class)->except(["index"]);
    Route::get("quizzes/{quiz}/questions", [QuestionController::class, "index"]);
    Route::get("question-types/{questionType}", [QuestionTypeController::class, "show"]);
    Route::post("game-sessions", [GameSessionController::class, "store"]);
    Route::get("game-sessions/{gameSession}", [GameSessionController::class, "show"]);
    Route::post("game-sessions/{gameSession}/start", [GameSessionController::class, "start"]);
    Route::post("game-sessions/{gameSession}/next-question", [GameSessionController::class, "nextQuestion"]);
    Route::post("game-sessions/{gameSession}/end", [GameSessionController::class, "end"]);
});


