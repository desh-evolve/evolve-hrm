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
        Schema::create('emp_bank_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->string('bank_code')->nullable();  // Bank code
            $table->string('bank_name');  // Bank name
            $table->string('bank_branch');  // Bank branch
            $table->string('account_number');  // Account number

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
        Schema::dropIfExists('emp_bank_details');
    }
};
