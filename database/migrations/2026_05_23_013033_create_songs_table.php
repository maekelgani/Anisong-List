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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->constrained('franchises')->nullOnDelete();
            $table->string('anime_name')->nullable();
            $table->string('judul_lagu');
            $table->string('penyanyi');
            $table->enum('tipe', ['opening', 'ending', 'movie']);
            $table->decimal('score', 4, 2)->default(0); // Misal score dari 0.00 - 10.00
            $table->string('link_video')->nullable();
            $table->integer('tahun_rilis');
            $table->integer('peringkat'); // 1-100
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
