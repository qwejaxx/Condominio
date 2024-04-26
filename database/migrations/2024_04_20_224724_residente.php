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
        Schema::create('residente', function (Blueprint $table) {
            $table->increments('id_rsdt');
            $table->string('ci_rsdt', 15)->unique();
            $table->string('nombre_rsdt', 20);
            $table->string('apellidop_rsdt', 20);
            $table->string('apellidom_rsdt', 20)->nullable();
            $table->date('fechanac_rsdt');
            $table->string('telefono_rsdt', 20);
            $table->unsignedBigInteger('usuario_id_rsdt')->nullable();
            $table->foreign('usuario_id_rsdt')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('rep_fam_id_rsdt')->nullable();
            $table->foreign('rep_fam_id_rsdt')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->index('nombre_rsdt');
            $table->index('apellidop_rsdt');
            $table->index('apellidom_rsdt');
        });

        /* Schema::create('residente', function (Blueprint $table) {
            $table->increments('id_rsdt');
            $table->string('ci_rsdt', 15)->unique();
            $table->string('nombre_rsdt', 20);
            $table->string('apellidop_rsdt', 20);
            $table->string('apellidom_rsdt', 20)->nullable();
            $table->date('fechanac_rsdt');
            $table->string('telefono_rsdt', 20);
            $table->string('rol_rsdt', 50);
            $table->string('usuario_rsdt', 50)->nullable();
            $table->string('contrasena_rsdt', 255)->nullable();
            $table->string('estado_rsdt', 20);
            $table->unsignedInteger('rep_fam_id_rsdt')->nullable();
            $table->foreign('rep_fam_id_rsdt')->references('id_rsdt')->on('residente')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        }); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residente', function (Blueprint $table) {
            $table->dropIndex(['nombre_rsdt']);
            $table->dropIndex(['apellidop_rsdt']);
            $table->dropIndex(['apellidom_rsdt']);
        });
    }
};
