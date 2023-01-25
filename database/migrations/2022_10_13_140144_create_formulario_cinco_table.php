<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioCincoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_cinco', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("memoria_id");
            $table->date("fecha_registro");
            $table->timestamps();
            $table->foreign("memoria_id")->on("memoria_calculos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_cinco');
    }
}
