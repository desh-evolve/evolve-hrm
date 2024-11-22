<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('schedule_policy', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->unsignedInteger('company_id'); // Foreign key to companies
            $table->string('name', 250); // Name of the schedule policy
            $table->unsignedInteger('meal_policy_id')->nullable(); // Foreign key to meal policies
            $table->unsignedInteger('over_time_policy_id')->nullable(); // Foreign key to overtime policies
            $table->unsignedInteger('absence_policy_id')->nullable(); // Foreign key to absence policies
            $table->unsignedInteger('start_window'); // Start window (integer value)
            $table->unsignedInteger('start_stop_window')->nullable(); // Optional start-stop window
            
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
        Schema::dropIfExists('schedule_policy');
    }
};
