<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');            
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('cover')->nullable();
            $table->enum('type', ['album', 'single', 'ep'])->default('album');
            $table->enum('status', ['y', 'n'])->default('y');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};