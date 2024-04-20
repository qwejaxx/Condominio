<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planificacion', function (Blueprint $table) {
            $table->increments('id_plan');
            $table->string('motivo_plan', 255);
            $table->string('descripcion_plan', 255);
            $table->string('area_plan', 255)->nullable();
            $table->decimal('pago_plan', 10, 2)->nullable();
            $table->dateTime('inicio_plan');
            $table->dateTime('fin_plan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificacion');
    }
};
