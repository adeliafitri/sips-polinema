<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpmkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps');
            $table->foreignId('cpl_id')->constrained('cpl');
            $table->string('kode_cpmk')->references('id')->on('cpmk')->onDelete('no action');
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('cpmk');
    }
}
