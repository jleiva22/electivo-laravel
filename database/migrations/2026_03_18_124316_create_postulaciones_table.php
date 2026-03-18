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
        if (! Schema::hasTable('postulaciones')) {
            Schema::create('postulaciones', function (Blueprint $table) {
                $table->increments('id');
                $table->string('descripcion', 255);
                $table->enum('estado', ['activa', 'cerrada'])->default('activa');
                $table->integer('max_total_electivos')->default(1);
                $table->dateTime('fecha_inicio')->nullable();
                $table->dateTime('fecha_termino')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulaciones');
    }
};
