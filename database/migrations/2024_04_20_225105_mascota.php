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
        Schema::create('mascota', function (Blueprint $table) {
            $table->increments('id_mas');
            $table->string('nombre_mas', 30);
            $table->string('tipo_mas', 30);
            $table->unsignedInteger('propietario_id_mas');
            $table->foreign('propietario_id_mas')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascota');
    }
};
