<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Efectivo
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2)->default(0);
            // descripcion del moviento en caja
            $table->string('concepto', 250)->nullable();
            // manejar comprobante-añadir foto o img
            $table->string('comprobante', 100)->nullable();
            // tipo de movimiento- ingreso o gasto
            $table->enum('tipo', ['INGRESO', 'GASTO'])->default('INGRESO');
            // llave foranea del user que sube este movimiento
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('cajas');
    }
}
