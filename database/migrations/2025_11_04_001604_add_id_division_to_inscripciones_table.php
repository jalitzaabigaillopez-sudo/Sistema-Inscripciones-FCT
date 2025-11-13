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
            //
           $table->unsignedInteger('division_id')->nullable()->after('id_categoria');
         
    if (!Schema::hasColumn('inscripciones', 'monto')) {
        $table->decimal('monto', 10, 2)->nullable()->after('id_academia');
    }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            
        });
    }
};
