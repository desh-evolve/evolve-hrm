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
        Schema::create('overtime_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->integer('type_id')->unsigned()->comment('overtime_types->id');
            $table->integer('trigger_time')->unsigned()->comment('time in seconds');
            $table->integer('max_time')->unsigned()->comment('time in seconds');
            $table->decimal('rate', 9, 4)->nullable();
            $table->integer('accrual_policy_id')->unsigned()->nullable();
            $table->decimal('accrual_rate', 9, 4)->nullable();
            $table->integer('pay_stub_entry_account_id')->unsigned()->nullable();
            $table->integer('wage_group_id')->unsigned()->default(0);

            $table->string('status')->default('active')->nullable();
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
        Schema::dropIfExists('overtime_policy');
    }
};
