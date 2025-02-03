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
        Schema::create('report_schedule', function (Blueprint $table) {
            $table->id(); // Equivalent to bigint(20) unsigned AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('user_report_data_id')->unique();
            $table->unsignedInteger('state_id')->default(10);
            $table->unsignedInteger('priority_id');
            $table->string('name', 250)->nullable();
            $table->string('description', 250)->nullable();
            $table->timestamp('last_run_date')->useCurrent()->useCurrentOnUpdate();
            $table->integer('last_run_processing_time')->nullable();
            $table->integer('average_processing_time')->nullable();
            $table->bigInteger('total_processing_time')->nullable();
            $table->integer('total_runs')->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->useCurrent()->nullable();
            $table->string('custom_frequency_id', 250)->nullable();
            $table->string('custom_frequency_data', 250)->nullable();
            $table->string('minute', 250)->nullable();
            $table->string('hour', 250)->nullable();
            $table->string('day_of_month', 250)->nullable();
            $table->string('month', 250)->nullable();
            $table->string('day_of_week', 250)->nullable();
            $table->smallInteger('home_email_cc')->default(0);
            $table->string('other_email', 250)->nullable();
            
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
        Schema::dropIfExists('report_schedule');
    }
};
