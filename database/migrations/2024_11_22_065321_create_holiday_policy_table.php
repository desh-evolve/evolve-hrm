<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('holiday_policy', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('company_id'); // Foreign key to companies
            $table->string('name', 250); // Holiday policy name
            $table->string('type')->nullable()->comment('standard/advanced_fixed/advanced_average');
            $table->string('default_schedule_status')->nullable()->comment('working/absent'); // Default schedule status
            $table->unsignedInteger('minimum_employed_days'); // Minimum employed days
            $table->unsignedInteger('minimum_worked_period_days')->nullable(); // Minimum worked period days
            $table->unsignedInteger('minimum_worked_days')->nullable(); // Minimum worked days
            $table->unsignedInteger('average_time_days')->nullable(); // Average time days
            $table->boolean('include_over_time')->default(0); // Include overtime
            $table->boolean('include_paid_absence_time')->default(0); // Include paid absence time
            $table->unsignedInteger('minimum_time')->nullable(); // Minimum time
            $table->unsignedInteger('maximum_time')->nullable(); // Maximum time
            $table->unsignedInteger('time')->nullable(); // Time
            $table->unsignedInteger('absence_policy_id')->nullable(); // Foreign key to absence policies
            $table->unsignedInteger('round_interval_policy_id')->nullable(); // Foreign key to round interval policies

            $table->boolean('force_over_time_policy')->default(0); // Force overtime policy
            $table->boolean('average_time_worked_days')->default(1); // Average time worked days
            $table->string('worked_scheduled_days')->default('calendar_days'); // Worked scheduled days
            $table->unsignedInteger('minimum_worked_after_period_days')->default(0); // Minimum worked after period days
            $table->unsignedInteger('minimum_worked_after_days')->default(0); // Minimum worked after days
            $table->unsignedSmallInteger('worked_after_scheduled_days')->default(0); // Worked after scheduled days
            $table->unsignedSmallInteger('paid_absence_as_worked')->default(0); // Paid absence as worked
            $table->unsignedInteger('average_days')->nullable(); // Average days

            // Audit fields
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('holiday_policy');
    }
};
