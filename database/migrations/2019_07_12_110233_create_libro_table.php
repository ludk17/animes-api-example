<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibroTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->text('resumen');
            $table->string('url_imagen');
            $table->string('titulo');
            $table->string('autor');
            $table->string('fecha_publicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('libros');
    }
}