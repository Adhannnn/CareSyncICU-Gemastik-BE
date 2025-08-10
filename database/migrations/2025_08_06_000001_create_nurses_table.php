<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurses', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('password');
        $table->text('face_image')->nullable(); // Pastikan baris ini ada
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};