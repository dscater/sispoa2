<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleFormulariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_formularios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("formulario_id");
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("formulario_id")->on("formulario_cuatro")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_formularios');
    }
}
