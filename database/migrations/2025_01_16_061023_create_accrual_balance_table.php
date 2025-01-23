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
        Schema::create('accrual_balance', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->unsignedInteger('user_id'); // Foreign key to user
            $table->unsignedInteger('accrual_policy_id'); // Foreign key to accrual policy
            $table->decimal('balance', 18, 4)->nullable(); // Accrual balance
            $table->unsignedInteger('banked_ytd')->default(0); // Banked Year-To-Date
            $table->unsignedInteger('used_ytd')->default(0); // Used Year-To-Date
            $table->unsignedInteger('awarded_ytd')->default(0); // Awarded Year-To-Date

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
        Schema::dropIfExists('accrual_balance');
    }
};
