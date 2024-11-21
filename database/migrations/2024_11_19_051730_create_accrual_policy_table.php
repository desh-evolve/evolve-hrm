<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('accrual_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->string('type')->nullable()->comment('standard/calendar_based/hour_based');
            $table->integer('minimum_time')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('maximum_time')->unsigned()->nullable()->comment('time in seconds');
            $table->string('apply_frequency')->nullable()->comment('pay_period/annually/monthly/weekly');
            $table->smallInteger('apply_frequency_month')->nullable();
            $table->smallInteger('apply_frequency_day_of_month')->nullable();
            $table->smallInteger('apply_frequency_day_of_week')->nullable();
            $table->smallInteger('milestone_rollover_hire_date')->nullable();
            $table->smallInteger('milestone_rollover_month')->nullable();
            $table->smallInteger('milestone_rollover_day_of_month')->nullable();
            $table->integer('minimum_employed_days')->unsigned()->nullable();
            $table->smallInteger('minimum_employed_days_catchup')->nullable();
            $table->boolean('enable_pay_stub_balance_display')->default(0);
            $table->boolean('apply_frequency_hire_date')->default(0);

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accrual_policy');
    }
};
