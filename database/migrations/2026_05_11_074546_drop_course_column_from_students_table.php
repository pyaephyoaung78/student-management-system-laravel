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
        Schema::table('students', function (Blueprint $table) {
            //
            $table->dropColumn('course');
        });
    }

    /**
     * Reverse the migrations.
     */
    // drop_course_column_from_students_table.php
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('course')->nullable();
        });
    }
};
