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
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('curso')->nullable()->after('nombre_completo');
            $table->string('telefono')->nullable()->after('curso');
            $table->date('fecha_nacimiento')->nullable()->after('telefono');
            $table->string('direccion')->nullable()->after('fecha_nacimiento');
            $table->string('comuna')->nullable()->after('direccion');
            $table->string('region')->nullable()->after('comuna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn([
                'rut',
                'curso',
                'telefono',
                'fecha_nacimiento',
                'direccion',
                'comuna',
                'region',
            ]);
        });
    }
};
