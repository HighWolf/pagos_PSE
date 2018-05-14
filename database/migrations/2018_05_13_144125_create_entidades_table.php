<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipo_doc');
            $table->string('documento', 12);
            $table->string('nombres', 60);
            $table->string('apellidos', 60);
            $table->string('compania', 60)->nullable();
            $table->string('email', 80);
            $table->string('direccion', 100);
            $table->unsignedInteger('ciudad');
            $table->string('telefono', 30)->nullable();
            $table->string('celular', 30)->nullable();
            $table->timestamps();
            $table->foreign('tipo_doc')->references('id')->on('tipo_documentos');
            $table->foreign('ciudad')->references('codigo_dane')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidades');
    }
}
