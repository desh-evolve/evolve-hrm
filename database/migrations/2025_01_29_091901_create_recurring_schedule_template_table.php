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
        Schema::create('recurring_schedule_template', function (Blueprint $table) {
            $table->id(); // Equivalent to int(11) AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('recurring_schedule_template_control_id');
            $table->unsignedInteger('week');

            // Boolean fields for each day of the week
            $table->boolean('sun')->default(0);
            $table->boolean('mon')->default(0);
            $table->boolean('tue')->default(0);
            $table->boolean('wed')->default(0);
            $table->boolean('thu')->default(0);
            $table->boolean('fri')->default(0);
            $table->boolean('sat')->default(0);

            // Timestamps for schedule start and end time
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            // Foreign key references
            $table->unsignedInteger('schedule_policy_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('job_id')->nullable();
            $table->unsignedInteger('job_item_id')->nullable();

            // Audit fields
            $table->string('status')->default('active')->nullable()->comment('active, delete');
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
        Schema::dropIfExists('recurring_schedule_template');
    }
};
