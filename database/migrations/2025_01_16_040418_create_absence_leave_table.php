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
        Schema::create('absence_leave', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->string('name', 50); // Name of the leave
            $table->string('short_code', 20); // Short code for leave
            $table->integer('time_seconds'); // Time in seconds
            $table->unsignedInteger('related_leave_id'); // Related leave ID
            $table->unsignedInteger('related_leave_unit'); // Related leave unit

            // Audit fields
            $table->string('status')->default('active')->comment('active/delete')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_leave');
    }
};
