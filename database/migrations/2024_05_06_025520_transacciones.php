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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->increments('id_tr');
            $table->unsignedInteger('plan_id_tr');
            $table->unsignedInteger('residente_id_tr');
            $table->string('tipo_tr');
            $table->decimal('monto_tr', 10, 2);
            $table->dateTime('fecha_tr');
            $table->foreign('plan_id_tr')->references('id_plan')->on('planificacion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('residente_id_tr')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
