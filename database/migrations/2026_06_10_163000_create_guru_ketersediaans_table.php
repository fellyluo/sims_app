<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_ketersediaans', function (Blueprint $table) {
            $table->id();
            $table->string('id_guru', 36);
            $table->integer('hari');
            $table->integer('jam_ke');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_ketersediaans');
    }
};

