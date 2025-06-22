<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        "quiz_id",
        "session_code",
        "status",
        "current_question_index",
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}


