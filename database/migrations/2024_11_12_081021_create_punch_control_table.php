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
        Schema::create('punch_control', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_date_id');
            $table->integer('branch_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('total_time')->default(0);
            $table->integer('actual_total_time')->default(0);
            $table->integer('meal_policy_id')->nullable();
            $table->boolean('overlap')->default(0);
            $table->string('note', 1024)->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
            
            $table->index('user_date_id', 'punch_control_user_date_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punch_control');
    }
};
