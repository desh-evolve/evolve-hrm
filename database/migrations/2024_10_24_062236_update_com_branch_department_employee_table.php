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
        Schema::table('com_branch_department_employees', function (Blueprint $table) {
            // Drop the br_dep_id column
            $table->dropColumn('br_dep_id');
            
            // Add new branch_id and department_id columns
            $table->unsignedBigInteger('branch_id')->after('id');  // Adjust the position as needed
            $table->unsignedBigInteger('department_id')->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('com_branch_department_employees', function (Blueprint $table) {
            // Add back br_dep_id column
            $table->unsignedBigInteger('br_dep_id')->after('id');

            // Remove branch_id and department_id columns
            $table->dropColumn('branch_id');
            $table->dropColumn('department_id');
        });
    }
};
