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
        Schema::create('guest_rating_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_guest');
            $table->enum('tipe_rate', ['opening', 'ending', 'movie', 'all']);
            $table->enum('limit_top', ['10', '25', '50', '100']);
            $table->decimal('rata_rata_score', 4, 2)->nullable();
            $table->text('komentar_guest')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_rating_sessions');
    }
};
