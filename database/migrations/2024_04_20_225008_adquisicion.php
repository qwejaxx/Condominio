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
        Schema::create('adquisicion', function (Blueprint $table) {
            $table->increments('id_reg');
            $table->unsignedInteger('departamento_id_reg');
            $table->unsignedInteger('residente_id_reg');
            $table->string('tipoadq_reg', 20);
            $table->dateTime('inicio_reg');
            $table->dateTime('fin_reg');
            $table->decimal('pago_reg', 10, 2);
            $table->foreign('departamento_id_reg')->references('id_dpto')->on('departamento')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('residente_id_reg')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adquisicion');
    }
};
