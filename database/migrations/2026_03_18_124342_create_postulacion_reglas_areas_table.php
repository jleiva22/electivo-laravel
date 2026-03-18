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
        if (! Schema::hasTable('postulacion_reglas_areas')) {
            Schema::create('postulacion_reglas_areas', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('postulacion_id');
                $table->integer('area_id');
                $table->integer('max_permitido')->default(1);
                $table->timestamps();

                $table->unique(['postulacion_id', 'area_id']);

                $table->foreign('postulacion_id')->references('id')->on('postulaciones')->cascadeOnDelete();
                $table->foreign('area_id')->references('id')->on('areas')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacion_reglas_areas');
    }
};
