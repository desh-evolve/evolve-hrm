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
        Schema::create('emp_work_experience', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->string('company');  // Name of the company
            $table->date('from_date');  // Start date of employment
            $table->date('to_date');  // End date of employment, nullable if still employed
            $table->string('department')->nullable();  // Department name
            $table->string('designation');  // Job title or designation
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('emp_work_experience');
    }
};
