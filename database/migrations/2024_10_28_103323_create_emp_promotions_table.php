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
        Schema::create('emp_promotions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');  // Foreign key to employees table
            $table->string('current_designation');  // Employee's current designation
            $table->string('new_designation');  // Employee's new designation
            $table->decimal('current_salary', 15, 2);  // Current salary
            $table->decimal('new_salary', 15, 2);  // New salary
            $table->date('effective_date');  // Date when the new designation and salary take effect
            $table->text('remarks')->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_promotions');
    }
};
