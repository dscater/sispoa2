<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificacionDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("certificacion_id");
            $table->unsignedBigInteger("mo_id");
            $table->unsignedBigInteger("mod_id");
            $table->double("total_cantidad");
            $table->double("cantidad_usar");
            $table->double("saldo_cantidad");
            $table->decimal("total", 24, 2);
            $table->decimal("presupuesto_usarse", 24, 2);
            $table->decimal("saldo_total", 24, 2);
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
        Schema::dropIfExists('certificacion_detalles');
    }
}
