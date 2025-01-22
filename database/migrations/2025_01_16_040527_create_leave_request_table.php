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
        Schema::create('leave_request', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->unsignedInteger('company_id'); // Foreign key to company
            $table->unsignedInteger('user_id'); // Foreign key to user
            $table->unsignedInteger('designation_id'); // Foreign key to designation
            $table->unsignedInteger('accurals_policy_id'); // Foreign key to accruals policy
            $table->double('amount'); // Leave amount
            $table->date('leave_from'); // Start date of leave
            $table->date('leave_to'); // End date of leave
            $table->string('reason', 200); // Reason for leave
            $table->string('address_telephone', 200); // Contact information
            $table->unsignedInteger('covered_by'); // ID of the covering employee
            $table->unsignedInteger('supervisor_id'); // Supervisor's ID
            $table->unsignedInteger('method'); // Method of leave
            $table->boolean('is_covered_approved')->default(0); // Covered approval flag
            $table->boolean('is_supervisor_approved')->default(0); // Supervisor approval flag
            $table->boolean('is_hr_approved')->default(0); // HR approval flag
            $table->string('leave_time', 20); // Start time of leave
            $table->string('leave_end_time', 20); // End time of leave
            $table->string('leave_dates', 2000); // Detailed leave dates (comma-separated or JSON)

            $table->string('status', 25)->default('pending')->comment('pending/cover_rejected/supervisor_rejected/hr_rejected/delete');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request');
    }
};
