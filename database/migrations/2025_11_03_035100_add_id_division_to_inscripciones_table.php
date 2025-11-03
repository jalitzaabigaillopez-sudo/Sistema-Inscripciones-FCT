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
        Schema::table('inscripciones', function (Blueprint $table) {
        $table->unsignedBigInteger('id_division')->nullable()->after('id_categoria');

        // Si tenés la tabla `divisiones`, agregá la clave foránea:
        $table->foreign('id_division')->references('id_division')->on('divisiones')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
        $table->dropForeign(['id_division']);
        $table->dropColumn('id_division');
    });
    }
};
