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
        Schema::create('emp_qualifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');  // Foreign key to employees table
            $table->string('qualification');  // Name or type of qualification
            $table->string('institute');  // Institute name
            $table->string('year');  // Year of completion
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
        Schema::dropIfExists('emp_qualifications');
    }
};
