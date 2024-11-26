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
        Schema::create('policy_group_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('policy_group_id')->nullable();
            $table->string('policy_table', 30)->nullable()->comment('policy type');
            $table->unsignedInteger('policy_id')->nullable();

            // Audit fields
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_group_policies');
    }
};
