<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCplTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('cpl', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cpl');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_cpl', ['Sikap', 'Pengetahuan', 'Keterampilan Umum', 'Keterampilan Khusus']);
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
        Schema::dropIfExists('cpl');
    }
}
