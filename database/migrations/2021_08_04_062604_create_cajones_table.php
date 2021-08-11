<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajones', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('estatus', ['DISPONIBLE', 'OCUPADO'])->default('DISPONIBLE');
            // relacionar cajones con tipos
            // Campo sin signo, entero y positivo
            $table->unsignedBigInteger('tipo_id');
            // campo con relacion de tabla tipos
            $table->foreign('tipo_id')->references('id_tipo')->on('tipos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cajones');
    }
}
