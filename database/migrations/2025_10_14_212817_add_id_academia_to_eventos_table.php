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
           Schema::table('eventos', function (Blueprint $table) {
            // Verificamos que la columna no exista aún
            if (!Schema::hasColumn('eventos', 'id_academia')) {
                // Aseguramos compatibilidad con el tipo de campo de academias
                $table->unsignedInteger('id_academia')->nullable()->after('id_tipo_evento');

                // Agregamos la relación
                $table->foreign('id_academia')
                    ->references('id_academia')
                    ->on('academias')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['id_academia']);
            $table->dropColumn('id_academia');
        });
    }
};
