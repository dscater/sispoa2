<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("detalle_formulario_id");
            $table->string("codigo_operacion", 255);
            $table->text("operacion");
            $table->timestamps();

            $table->foreign("detalle_formulario_id")->on("detalle_formularios")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operacions');
    }
}
