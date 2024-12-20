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
        Schema::create('pay_company_deduction', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('name', 250);
            $table->string('calculation_type')->default('percent');
            $table->integer('calculation_order')->default(0);
            $table->string('country', 250)->nullable();
            $table->string('province', 250)->nullable();
            $table->string('district', 250)->nullable();
            $table->string('company_value1', 250)->nullable();
            $table->string('company_value2', 250)->nullable();
            $table->string('user_value1', 250)->nullable();
            $table->string('user_value2', 250)->nullable();
            $table->string('user_value3', 250)->nullable();
            $table->string('user_value4', 250)->nullable();
            $table->string('user_value5', 250)->nullable();
            $table->string('user_value6', 250)->nullable();
            $table->string('user_value7', 250)->nullable();
            $table->string('user_value8', 250)->nullable();
            $table->string('user_value9', 250)->nullable();
            $table->string('user_value10', 250)->nullable();
            $table->boolean('lock_user_value1')->default(false);
            $table->boolean('lock_user_value2')->default(false);
            $table->boolean('lock_user_value3')->default(false);
            $table->boolean('lock_user_value4')->default(false);
            $table->boolean('lock_user_value5')->default(false);
            $table->boolean('lock_user_value6')->default(false);
            $table->boolean('lock_user_value7')->default(false);
            $table->boolean('lock_user_value8')->default(false);
            $table->boolean('lock_user_value9')->default(false);
            $table->boolean('lock_user_value10')->default(false);
            $table->integer('pay_stub_entry_account_id')->unsigned();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->decimal('minimum_length_of_service', 11, 4)->nullable();
            $table->string('minimum_length_of_service_unit')->nullable();
            $table->decimal('minimum_length_of_service_days', 11, 4)->nullable();
            $table->decimal('maximum_length_of_service', 11, 4)->nullable();
            $table->string('maximum_length_of_service_unit')->nullable();
            $table->decimal('maximum_length_of_service_days', 11, 4)->nullable();
            $table->string('include_account_amount_type')->nullable();
            $table->string('exclude_account_amount_type')->nullable();
            $table->decimal('minimum_user_age', 11, 4)->nullable();
            $table->decimal('maximum_user_age', 11, 4)->nullable();
            $table->string('basis_of_employment')->default('contract');

            // Audit fields
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
        Schema::dropIfExists('pay_company_deduction');
    }
};
