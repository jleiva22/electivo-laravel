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
        if (! Schema::hasTable('seleccion_electivos')) {
            Schema::create('seleccion_electivos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('postulacion_alumno_id');
                $table->integer('electivo_id');
                $table->timestamp('created_at')->useCurrent();

                $table->unique(['postulacion_alumno_id', 'electivo_id'], 'seleccion_unica');
                $table->index('electivo_id');

                $table->foreign('postulacion_alumno_id')->references('id')->on('postulacion_alumnos')->cascadeOnDelete();
                $table->foreign('electivo_id')->references('id')->on('electivos')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seleccion_electivos');
    }
};
