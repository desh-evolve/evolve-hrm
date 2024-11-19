<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('accrual_policy_milestone', function (Blueprint $table) {
            $table->id();
            $table->integer('accrual_policy_id')->unsigned();
            $table->decimal('length_of_service', 9, 2)->nullable();
            $table->smallInteger('length_of_service_unit_id')->nullable();
            $table->decimal('length_of_service_days', 9, 2)->nullable();
            $table->decimal('accrual_rate', 18, 4)->nullable();
            $table->integer('minimum_time')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('maximum_time')->unsigned()->nullable()->comment('time in seconds');
            $table->integer('rollover_time')->unsigned()->nullable()->comment('time in seconds');

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accrual_policy_milestone');
    }
};
