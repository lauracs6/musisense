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
        Schema::create('audio_features', function (Blueprint $table) {
            $table->foreignId('track_id')->primary()->constrained()->cascadeOnDelete();
            $table->float('danceability')->nullable();
            $table->float('energy')->nullable();
            $table->float('tempo')->nullable();
            $table->float('loudness')->nullable();
            $table->float('valence')->nullable();
            $table->float('acousticness')->nullable();
            $table->float('instrumentalness')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_features');
    }
};
