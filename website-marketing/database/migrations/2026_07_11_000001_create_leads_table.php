<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('nama');
            $table->string('sekolah');
            $table->string('jabatan')->nullable();
            $table->string('email');
            $table->string('no_hp', 30)->nullable();
            $table->unsignedInteger('perkiraan_siswa')->nullable();
            $table->string('tier_diminati', 20)->nullable();
            $table->text('pesan')->nullable();
            $table->string('sumber', 20);
            $table->timestamps();

            $table->index(['created_at', 'sumber']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
