<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('premium_policy', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->string('type')->nullable()->comment('date_time/shift_differential/meal_break/callback/minimum_shift_time/holiday/advanced');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Weekday flags
            $table->boolean('sun')->default(0);
            $table->boolean('mon')->default(0);
            $table->boolean('tue')->default(0);
            $table->boolean('wed')->default(0);
            $table->boolean('thu')->default(0);
            $table->boolean('fri')->default(0);
            $table->boolean('sat')->default(0);

            $table->string('pay_type')->nullable()->comment('pay_multiplied/pay_plus_premium/flat_hourly_rate');
            $table->decimal('rate', 9, 4)->nullable();
            $table->integer('accrual_policy_id')->unsigned()->nullable();
            $table->decimal('accrual_rate', 9, 4)->nullable();
            $table->integer('pay_stub_entry_account_id')->unsigned()->nullable();

            // Additional fields
            $table->integer('daily_trigger_time')->unsigned()->nullable();
            $table->integer('weekly_trigger_time')->unsigned()->nullable();
            $table->integer('minimum_time')->unsigned()->nullable();
            $table->integer('maximum_time')->unsigned()->nullable();
            $table->smallInteger('include_meal_policy')->nullable();
            $table->smallInteger('exclude_default_branch')->nullable();
            $table->smallInteger('exclude_default_department')->nullable();

            // Selection type IDs
            $table->smallInteger('branch_selection_type_id')->nullable();
            $table->smallInteger('department_selection_type_id')->nullable();
            $table->smallInteger('job_selection_type_id')->nullable();
            $table->smallInteger('job_group_selection_type_id')->nullable();
            $table->smallInteger('job_item_selection_type_id')->nullable();
            $table->smallInteger('job_item_group_selection_type_id')->nullable();

            // Break and shift settings
            $table->integer('maximum_no_break_time')->unsigned()->nullable();
            $table->integer('minimum_break_time')->unsigned()->nullable();
            $table->boolean('include_partial_punch')->default(0);
            $table->integer('wage_group_id')->unsigned()->default(0);
            $table->smallInteger('include_break_policy')->default(0);
            $table->integer('minimum_time_between_shift')->unsigned()->nullable();
            $table->integer('minimum_first_shift_time')->unsigned()->nullable();
            $table->integer('minimum_shift_time')->unsigned()->nullable();
            
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
        Schema::dropIfExists('premium_policy');
    }
};
