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
        Schema::create('visita', function (Blueprint $table) {
            $table->increments('id_vis');
            $table->unsignedInteger('visitante_id_vis');
            $table->unsignedInteger('visitado_id_vis');
            $table->dateTime('fecha_vis');
            $table->foreign('visitante_id_vis')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('visitado_id_vis')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita');
    }
};
