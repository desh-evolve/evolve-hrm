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
        Schema::create('pay_user_deduction', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('company_deduction_id')->unsigned();
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
        Schema::dropIfExists('pay_user_deduction');
    }
};
