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
        Schema::create('recurring_schedule_template_control', function (Blueprint $table) {
            $table->id(); // Equivalent to int(11) AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('company_id');
            $table->string('name', 250);
            $table->string('description', 250)->nullable();

            // Audit fields
            $table->string('status')->default('active')->nullable()->comment('active, delete');
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
        Schema::dropIfExists('recurring_schedule_template_control');
    }
};
