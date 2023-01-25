<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleOperacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_operacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("operacion_id");
            $table->double("ponderacion", 8, 2);
            $table->string("resultado_esperado", 255);
            $table->string("medios_verificacion", 255);
            $table->string("codigo_tarea", 255);
            $table->string("actividad_tarea", 255);
            $table->string("pt_e", 255)->nullable();
            $table->string("pt_f", 255)->nullable();
            $table->string("pt_m", 255)->nullable();
            $table->string("st_a", 255)->nullable();
            $table->string("st_m", 255)->nullable();
            $table->string("st_j", 255)->nullable();
            $table->string("tt_j", 255)->nullable();
            $table->string("tt_a", 255)->nullable();
            $table->string("tt_s", 255)->nullable();
            $table->string("ct_o", 255)->nullable();
            $table->string("ct_n", 255)->nullable();
            $table->string("ct_d", 255)->nullable();
            $table->date("inicio", 255);
            $table->date("final", 255);
            $table->timestamps();

            $table->foreign("operacion_id")->on("operacions")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_operacions');
    }
}
