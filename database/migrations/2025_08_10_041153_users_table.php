<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('role'); // misal 'patient' atau 'admin'
            $table->boolean('first_login')->default(true);
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->string('unique_code')->nullable(); // misal kode unik admin jika perlu
            $table->string('password')->nullable(); // jika nanti pakai password login
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
