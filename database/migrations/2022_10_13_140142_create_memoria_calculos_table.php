<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoriaCalculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memoria_calculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("formulario_id");
            $table->decimal("total_actividades", 24, 2);
            $table->decimal("total_ene", 24, 2);
            $table->decimal("total_feb", 24, 2);
            $table->decimal("total_mar", 24, 2);
            $table->decimal("total_abr", 24, 2);
            $table->decimal("total_may", 24, 2);
            $table->decimal("total_jun", 24, 2);
            $table->decimal("total_jul", 24, 2);
            $table->decimal("total_ago", 24, 2);
            $table->decimal("total_sep", 24, 2);
            $table->decimal("total_oct", 24, 2);
            $table->decimal("total_nov", 24, 2);
            $table->decimal("total_dic", 24, 2);
            $table->decimal("total_final", 24, 2);
            $table->date("fecha_registro");
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
        Schema::dropIfExists('memoria_calculos');
    }
}
