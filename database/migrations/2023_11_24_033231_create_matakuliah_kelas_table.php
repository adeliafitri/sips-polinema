<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatakuliahKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('matakuliah_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->foreignId('semester_id')->constrained('semester');
            $table->enum('koordinator', [1, 0])->default(0);
            $table->timestamps();
        });

        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matakuliah_kelas');
    }
}
