<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employee_date_total', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('employee_date_id'); 
            $table->string('punch_status', 20)->default('normal')->comment('normal/lunch/break'); 
            $table->string('punch_type', 20)->default('in')->comment('in/out'); 
            $table->unsignedInteger('punch_control_id')->nullable(); 
            $table->unsignedInteger('over_time_policy_id')->nullable(); 
            $table->unsignedInteger('absence_policy_id')->nullable(); 
            $table->unsignedInteger('premium_policy_id')->nullable(); 
            $table->unsignedInteger('meal_policy_id')->nullable(); 
            $table->unsignedInteger('break_policy_id')->default(0); 
            $table->unsignedInteger('branch_id')->nullable(); 
            $table->unsignedInteger('department_id')->nullable(); 
            $table->timestamp('start_time_stamp')->nullable(); 
            $table->timestamp('end_time_stamp')->nullable(); 
            $table->integer('total_time')->default(0); 
            $table->boolean('override')->default(0); 
            $table->integer('actual_total_time')->default(0); 
            $table->string('comment_ot', 250)->default(''); 

            // Audit fields
            $table->string('status', 25)->default('active')->comment('active/delete');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_date_total');
    }
};
