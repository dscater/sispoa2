<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoriaOperacionDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memoria_operacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->string("memoria_operacion_id", 255);
            $table->string("ue", 255);
            $table->string("prog", 255);
            $table->string("act", 255);
            $table->string("lugar", 255);
            $table->string("responsable", 255);
            $table->unsignedBigInteger("partida_id");
            $table->string("partida", 255);
            $table->text("descripcion");
            $table->string("nro", 255);
            $table->text("descripcion_detallada");
            $table->float("cantidad");
            $table->string("unidad");
            $table->decimal("costo", 24, 2);
            $table->decimal("total", 24, 2);
            $table->text("justificacion");
            $table->decimal("ene", 24, 2)->nullable();
            $table->decimal("feb", 24, 2)->nullable();
            $table->decimal("mar", 24, 2)->nullable();
            $table->decimal("abr", 24, 2)->nullable();
            $table->decimal("may", 24, 2)->nullable();
            $table->decimal("jun", 24, 2)->nullable();
            $table->decimal("jul", 24, 2)->nullable();
            $table->decimal("ago", 24, 2)->nullable();
            $table->decimal("sep", 24, 2)->nullable();
            $table->decimal("oct", 24, 2)->nullable();
            $table->decimal("nov", 24, 2)->nullable();
            $table->decimal("dic", 24, 2)->nullable();
            $table->decimal("total_actividad", 24, 2);
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
        Schema::dropIfExists('memoria_operacion_detalles');
    }
}
