<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormularioCuatroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulario_cuatro', function (Blueprint $table) {
            $table->id();
            $table->string("codigo_pei", 255);
            $table->string("accion_institucional", 255);
            $table->string("indicador", 255);
            $table->string("codigo_poa", 255);
            $table->string("accion_corto", 255);
            $table->string("resultado_esperado", 255);
            $table->decimal("presupuesto", 24, 2);
            $table->double("ponderacion", 8, 2);
            $table->unsignedBigInteger("unidad_id");
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("unidad_id")->on("unidads")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulario_cuatro');
    }
}
