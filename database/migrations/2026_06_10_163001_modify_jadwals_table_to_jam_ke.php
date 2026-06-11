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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
            $table->integer('jam_ke')->after('hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('jam_ke');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
        });
    }
};
