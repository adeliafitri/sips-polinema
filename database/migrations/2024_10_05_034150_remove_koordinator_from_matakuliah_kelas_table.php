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
        Schema::table('matakuliah_kelas', function (Blueprint $table) {
            $table->dropColumn('koordinator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matakuliah_kelas', function (Blueprint $table) {
            $table->enum('koordinator', [1, 0])->default(0);
        });
    }
};
