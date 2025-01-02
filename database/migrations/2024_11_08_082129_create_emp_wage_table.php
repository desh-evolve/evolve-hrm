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
        Schema::create('emp_wage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wage_group_id');
            $table->unsignedBigInteger('wage_type_id');
            
            $table->decimal('wage', 11, 2)->nullable();
            $table->decimal('budgetary_allowance', 11, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->integer('weekly_time')->nullable();
            $table->decimal('hourly_rate', 11, 2)->nullable();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('emp_wage');
    }
};
