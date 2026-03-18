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
        if (! Schema::hasTable('sectors')) {
            Schema::create('sectors', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('area_id');
                $table->string('nombre', 150);
                $table->text('descripcion')->nullable();
                $table->timestamps();

                $table->index('area_id');
                $table->foreign('area_id')->references('id')->on('areas')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};
