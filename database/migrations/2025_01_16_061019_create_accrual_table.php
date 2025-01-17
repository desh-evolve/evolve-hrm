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
        Schema::create('accrual', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->unsignedInteger('user_id'); // Foreign key to user
            $table->unsignedInteger('accrual_policy_id'); // Foreign key to accrual policy
            $table->string('type', 25)->nullable()->comment('awarded(30)/un_awarded(40)/gift(50)/paid_out(55)/rollover_adjustment(60)/initial_balance(70)/other(80)'); // Foreign key to type
            $table->unsignedInteger('user_date_total_id')->nullable(); // Optional user date total ID
            $table->timestamp('time_stamp')->nullable(); // Timestamp for accrual
            $table->decimal('amount', 18, 4)->nullable(); // Amount of accrual
            $table->unsignedInteger('leave_requset_id'); // Foreign key to leave request

            $table->string('status', 25)->default('active')->comment('active/delete');
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
        Schema::dropIfExists('accrual');
    }
};
