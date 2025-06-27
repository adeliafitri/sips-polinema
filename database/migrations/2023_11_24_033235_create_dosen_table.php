<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_auth')->constrained('auth');
            $table->string('nama');
            $table->string('nidn')->unique();
            $table->string('telp')->unique();
            $table->string('email')->unique();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('dosen');
    }
}
