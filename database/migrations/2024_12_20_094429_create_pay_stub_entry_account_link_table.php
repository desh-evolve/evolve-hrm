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
        Schema::create('pay_stub_entry_account_link', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->unsignedInteger('company_id'); // Foreign key to company
            $table->string('total_gross')->nullable();
            $table->string('total_user_deduction')->nullable();
            $table->string('total_employer_deduction')->nullable();
            $table->string('total_net_pay')->nullable();
            $table->integer('regular_time')->nullable();
            $table->integer('monthly_advance')->nullable();
            $table->integer('monthly_advance_deduction')->nullable();
            $table->integer('user_cpp')->nullable();
            $table->integer('user_ei')->nullable();

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
        Schema::dropIfExists('pay_stub_entry_account_link');
    }
};
