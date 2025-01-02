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
        Schema::create('emp_kpi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->date('review_date');  // Date of the KPI review
            $table->unsignedBigInteger('reviewed_by');

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
        Schema::dropIfExists('emp_kpi');
    }
};
