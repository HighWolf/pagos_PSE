<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('banco');
            $table->unsignedInteger('interfaz_banco');
            $table->string('return_url', 255);
            $table->string('referencia_pago', 32);
            $table->string('descripcion', 255);
            $table->string('lenguaje', 2)->default('ES');
            $table->string('moneda', 3)->default('COP');
            $table->double('total');
            $table->double('tax')->default(0);
            $table->double('devolucion_base')->default(0);
            $table->double('propina')->default(0);
            $table->unsignedInteger('pagador');
            $table->unsignedInteger('comprador')->nullable();
            $table->unsignedInteger('receptor');
            $table->string('direccion_ip', 15);
            $table->string('user_agent', 255);
            $table->timestamps();
            $table->foreign('banco')->references('id')->on('bancos');
            $table->foreign('interfaz_banco')->references('id')->on('interfaz_bancos');
            $table->foreign('pagador')->references('id')->on('entidades');
            $table->foreign('comprador')->references('id')->on('entidades');
            $table->foreign('receptor')->references('id')->on('entidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacciones');
    }
}
