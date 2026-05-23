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
        Schema::create('guest_rating_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('guest_rating_sessions')->cascadeOnDelete();
            $table->foreignId('song_id')->constrained('songs')->cascadeOnDelete();
            $table->decimal('score_diberikan', 4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_rating_details');
    }
};
