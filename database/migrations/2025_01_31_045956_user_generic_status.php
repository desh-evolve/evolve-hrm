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
        Schema::create('user_generic_status', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('batch_id');
            $table->string('label', 1024)->nullable();
            $table->string('description', 1024)->nullable();
            $table->string('link', 1024)->nullable();

            // Audit fields
            $table->string('status')->nullable()->comment('failed(10), warning(20), success(30)');
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
        Schema::dropIfExists('user_generic_status');
    }
};
