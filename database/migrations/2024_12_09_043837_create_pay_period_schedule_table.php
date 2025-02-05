<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_period_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id'); 
            $table->string('name', 250); 
            $table->string('description', 250)->nullable(); 
            $table->string('type')->nullable()->comment('manual(5), weekly(10), bi-weekly(20), semi-monthly(30), monthly(50)'); 
            $table->boolean('primary_date_ldom')->nullable(); 
            $table->boolean('primary_transaction_date_ldom')->nullable(); 
            $table->boolean('primary_transaction_date_bd')->nullable(); 
            $table->boolean('secondary_date_ldom')->nullable(); 
            $table->boolean('secondary_transaction_date_ldom')->nullable();
            $table->boolean('secondary_transaction_date_bd')->nullable(); 
            $table->timestamp('anchor_date')->nullable(); 
            $table->timestamp('primary_date')->nullable(); 
            $table->timestamp('primary_transaction_date')->nullable(); 
            $table->timestamp('secondary_date')->nullable(); 
            $table->timestamp('secondary_transaction_date')->nullable(); 
            $table->integer('day_start_time')->nullable(); 
            $table->integer('day_continuous_time')->nullable(); 
            $table->string('start_week_day', 20)->nullable()->comment('eg: mon_sun/tue_mon...'); 
            $table->string('start_day_of_week')->nullable()->comment('eg: sunday/monday...'); 
            $table->smallInteger('transaction_date')->nullable(); 
            $table->smallInteger('primary_day_of_month')->nullable(); 
            $table->smallInteger('secondary_day_of_month')->nullable(); 
            $table->smallInteger('primary_transaction_day_of_month')->nullable(); 
            $table->smallInteger('secondary_transaction_day_of_month')->nullable(); 
            $table->string('transaction_date_bd', 30)->nullable()->comment('no(0), yes_prev(1 - previous business day), yes_next(2 - next business day), yes_closest(3 - closest business day)'); 
            $table->string('time_zone', 50)->nullable(); 
            $table->integer('new_day_trigger_time')->nullable(); 
            $table->integer('maximum_shift_time')->nullable(); 
            $table->string('shift_assigned_day', 50)->nullable()->comment('day_they_start_on(10), day_they_end_on(20), day_most_time_worked(30 - Day w/Most Time Worked), each_day(40 - Each Day (Split at Midnight))'); 
            $table->integer('timesheet_verify_before_end_date')->nullable(); 
            $table->integer('timesheet_verify_before_transaction_date')->nullable(); 
            $table->integer('timesheet_verify_notice_before_transaction_date')->nullable(); 
            $table->integer('timesheet_verify_notice_email')->nullable(); 
            $table->unsignedInteger('annual_pay_periods')->nullable(); 
            $table->string('timesheet_verify_type', 20)->default('disabled')->comment('disabled(10), employee_only(20), superior_only(30), employee_and_superior(40)'); 
            
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
        Schema::dropIfExists('pay_period_schedule');
    }
};
