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
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id'); // Auto-increment primary key
            $table->unsignedInteger('user_date_id');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->unsignedInteger('schedule_policy_id')->nullable();
            $table->unsignedInteger('absence_policy_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->integer('total_time')->nullable();

            // Audit fields
            $table->string('status')->nullable()->comment('working(10), absent(20)');
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
        Schema::dropIfExists('schedule');
    }
};
