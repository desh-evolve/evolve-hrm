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
        Schema::create('recurring_schedule_user', function (Blueprint $table) {
            $table->id(); // Equivalent to int(11) AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('recurring_schedule_control_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_schedule_user');
    }
};
