<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('meal_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->string('type')->nullable()->comment('auto_deduct/auto_add/normal');
            $table->integer('amount')->unsigned()->comment('time in seconds');
            $table->integer('trigger_time')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('start_window')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('window_length')->unsigned()->nullable()->comment('time in seconds');
            $table->smallInteger('include_lunch_punch_time')->nullable();
            $table->string('auto_detect_type')->nullable()->default('time_window')->comment('time_window/punch_time');
            $table->integer('minimum_punch_time')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('maximum_punch_time')->unsigned()->nullable()->comment('time in seconds');

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('meal_policy');
    }
};

