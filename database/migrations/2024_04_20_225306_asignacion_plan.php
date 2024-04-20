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
        Schema::create('asignacion_plan', function (Blueprint $table) {
            $table->increments('id_asip');
            $table->unsignedInteger('planificacion_id_asip');
            $table->unsignedInteger('participante_id_asip');
            $table->decimal('cuota_asip', 10, 2)->nullable();
            $table->boolean('pagado_asip')->nullable();
            $table->foreign('planificacion_id_asip')->references('id_plan')->on('planificacion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('participante_id_asip')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_plan');
    }
};
