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
        if (! Schema::hasTable('postulacion_alumnos')) {
            Schema::create('postulacion_alumnos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('postulacion_id');
                $table->integer('alumno_id');
                $table->boolean('estado_cierre')->default(false);
                $table->dateTime('fecha_finalizacion')->nullable();

                $table->unique(['postulacion_id', 'alumno_id'], 'participacion_unica');
                $table->index('alumno_id');

                $table->foreign('postulacion_id')->references('id')->on('postulaciones')->cascadeOnDelete();
                $table->foreign('alumno_id')->references('id')->on('alumnos')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacion_alumnos');
    }
};
