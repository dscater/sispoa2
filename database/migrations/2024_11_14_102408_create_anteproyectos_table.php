<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnteproyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anteproyectos', function (Blueprint $table) {
            $table->id();
            $table->string("form4_excel", 255);
            $table->string("form5_excel", 255);
            $table->string("memoria_excel", 255);
            $table->string("certificacion_excel", 255);
            $table->string("form4_pdf", 255);
            $table->string("form5_pdf", 255);
            $table->string("memoria_pdf", 255);
            $table->string("certificacion_pdf", 255);
            $table->date("fecha_cierre");
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
        Schema::dropIfExists('anteproyectos');
    }
}
