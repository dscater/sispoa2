<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAprobacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aprobacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("unidad_id");
            $table->integer("estado");
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
        Schema::dropIfExists('aprobacions');
    }
}
