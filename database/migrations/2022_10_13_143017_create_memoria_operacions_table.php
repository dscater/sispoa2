<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoriaOperacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memoria_operacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("memoria_id");
            $table->unsignedBigInteger("operacion_id");
            $table->unsignedBigInteger("detalle_operacion_id");
            $table->decimal("total_operacion", 24, 2);
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("memoria_id")->on("memoria_calculos")->references("id");
            $table->foreign("operacion_id")->on("operacions")->references("id");
            $table->foreign("detalle_operacion_id")->on("detalle_operacions")->references("id");
            $table->foreign("partida_id")->on("partidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memoria_operacions');
    }
}
