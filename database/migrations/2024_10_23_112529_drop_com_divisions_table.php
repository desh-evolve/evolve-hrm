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
        // Drop the `com_divisions` table
        Schema::dropIfExists('com_divisions');

        // Remove the `division_id` column from `com_branch_department_users` table
        Schema::table('com_branch_department_users', function (Blueprint $table) {
            if (Schema::hasColumn('com_branch_department_users', 'division_id')) {
                $table->dropColumn('division_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the `com_divisions` table if rolled back
        Schema::create('com_divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->string('division_name');
            
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->nullable();
        });

        // Add `division_id` column back to `com_branch_department_users` table
        Schema::table('com_branch_department_users', function (Blueprint $table) {
            $table->integer('division_id')->nullable();
        });
    }
};
