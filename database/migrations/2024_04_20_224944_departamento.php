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
        Schema::create('departamento', function (Blueprint $table) {
            $table->increments('id_dpto');
            $table->string('codigo_dpto', 20)->unique();
            $table->decimal('precio_dpto', 10, 2);
            $table->decimal('precioa_dpto', 10, 2);
            $table->unsignedInteger('residente_id_dpto')->nullable();
            $table->unsignedInteger('parqueo_id_dpto')->nullable();
            $table->foreign('residente_id_dpto')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parqueo_id_dpto')->references('id_park')->on('parqueo')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};
