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
        if (! Schema::hasTable('alumnos')) {
            Schema::create('alumnos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('rut', 12)->unique();
                $table->string('nombre_completo', 255);
                $table->enum('nivel_actual', ['3', '4']);
                $table->timestamps();

                $table->index('user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
