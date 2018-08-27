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
            $table->string('bankCode', 8);
            $table->unsignedInteger('bankInterface');
            $table->string('returnURL', 255)->default('http://pagospse.com/transacciones/retorno');
            $table->string('reference', 32);
            $table->string('description', 255)->default('Descripción de prueba');
            $table->string('language', 2)->default('ES');
            $table->string('currency', 3)->default('COP');
            $table->double('totalAmount');
            $table->double('taxAmount')->default(0);
            $table->double('devolutionBase')->default(0);
            $table->double('tipAmount')->default(0);
            $table->unsignedInteger('payer');
            $table->unsignedInteger('buyer')->nullable();
            $table->unsignedInteger('shipping');
            $table->string('ipAddress', 15);
            $table->string('userAgent', 255);
            $table->unsignedInteger('transactionID')->nullable();
            $table->string('sessionID', 32)->nullable();
            $table->string('bankCurrency', 3)->nullable();
            $table->float('bankFactor')->nullable();
            $table->string('bankURL', 255)->nullable();
            $table->string('returnCode', 30)->nullable();
            $table->string('bankProcessDate')->nullable();
            $table->string('trazabilityCode', 40)->nullable();
            $table->Integer('transactionCycle')->nullable();
            $table->string('transactionState', 20)->nullable();
            $table->unsignedInteger('responseCode')->nullable();
            $table->string('responseReasonCode', 3)->nullable();
            $table->string('responseReasonText', 255)->nullable();
            $table->string('requestDate')->nullable();
            $table->boolean('onTest')->nullable();
            $table->timestamps();
            $table->foreign('bankCode')->references('bankCode')->on('bancos');
            $table->foreign('bankInterface')->references('id')->on('interfaz_bancos');
            $table->foreign('payer')->references('id')->on('entidades');
            $table->foreign('buyer')->references('id')->on('entidades');
            $table->foreign('shipping')->references('id')->on('entidades');
            $table->unique('reference');
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
