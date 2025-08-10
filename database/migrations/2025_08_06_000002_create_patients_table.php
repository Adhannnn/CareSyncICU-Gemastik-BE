<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('dokter_penjaga')->nullable();
            $table->string('jadwal_jenguk')->nullable();
            $table->text('kondisi')->nullable();
            $table->text('foto_pasien')->nullable(); // base64 atau url
            $table->string('key_access')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
