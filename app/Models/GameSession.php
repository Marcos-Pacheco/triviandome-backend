<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

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

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}


