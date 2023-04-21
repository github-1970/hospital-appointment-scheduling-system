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
        Schema::table('doctors', function (Blueprint $table) {
            $table->time('working_hours_start')->nullable();
            $table->time('working_hours_end')->nullable();
            $table->integer('max_appointments_per_hour')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('working_hours_start');
            $table->dropColumn('working_hours_end');
            $table->dropColumn('max_appointments_per_hour')->default(1);
        });
    }
};
