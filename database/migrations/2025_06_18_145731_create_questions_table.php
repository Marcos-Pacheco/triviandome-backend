<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("questions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("quiz_id")->constrained()->onDelete("cascade");
            $table->foreignId("question_type_id")->constrained()->onDelete("cascade");
            $table->text("text");
            $table->json("options")->nullable(); // Para múltipla escolha, verdadeiro/falso, etc.
            $table->string("correct_answer")->nullable(); // Resposta correta
            $table->integer("points")->default(100);
            $table->integer("time_limit")->default(20); // Tempo limite em segundos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("questions");
    }
};


