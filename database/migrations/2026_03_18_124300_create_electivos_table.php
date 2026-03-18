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
        if (! Schema::hasTable('electivos')) {
            Schema::create('electivos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sector_id');
                $table->string('nombre', 150);
                $table->text('descripcion_breve')->nullable();
                $table->string('pdf_path')->nullable();
                $table->enum('nivel_aplicacion', ['3', '4', 'comun'])->default('comun');
                $table->timestamps();

                $table->index('sector_id');
                $table->foreign('sector_id')->references('id')->on('sectors')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electivos');
    }
};
