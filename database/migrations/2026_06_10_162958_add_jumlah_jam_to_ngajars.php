<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ngajars', function (Blueprint $table) {
            $table->integer('jumlah_jam')->default(0)->after('id_kelas');
        });
    }

    public function down(): void
    {
        Schema::table('ngajars', function (Blueprint $table) {
            $table->dropColumn('jumlah_jam');
        });
    }
};

