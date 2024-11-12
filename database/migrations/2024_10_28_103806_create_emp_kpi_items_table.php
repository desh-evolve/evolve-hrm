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
        Schema::create('emp_kpi_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('emp_kpi_id');  // Foreign key referencing emp_kpi table
            $table->unsignedBigInteger('criteria_id');  // Foreign key referencing criteria table
            $table->decimal('score', 10, 2);  // Score for the KPI item
            $table->text('comments')->nullable();  // Comments on the KPI item, nullable

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
        Schema::dropIfExists('emp_kpi_items');
    }
};
