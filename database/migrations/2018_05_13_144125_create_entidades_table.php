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
            $table->unsignedInteger('documentType');
            $table->string('document', 12);
            $table->string('firstName', 60);
            $table->string('lastName', 60);
            $table->string('company', 60)->nullable();
            $table->string('emailAddress', 80);
            $table->string('address', 100);
            $table->unsignedInteger('city');
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->timestamps();
            $table->foreign('documentType')->references('id')->on('tipo_documentos');
            $table->foreign('city')->references('codigo_dane')->on('municipios');
            $table->unique('document');
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
