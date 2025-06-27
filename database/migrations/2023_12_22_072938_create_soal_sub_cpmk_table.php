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
        Schema::create('soal_sub_cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcpmk_id')->constrained('sub_cpmk');
            $table->foreignId('soal_id')->constrained('soal')->default(0);
            $table->float('bobot_soal');
            $table->string('waktu_pelaksanaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_sub_cpmk');
    }
};
