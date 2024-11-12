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
        Schema::create('com_wage_type', function (Blueprint $table) {
            $table->id();
            $table->string('wage_type'); 
            $table->string('name');  
            $table->string('number_of_weeks');  
            $table->string('wages_per_year');  
            $table->string('status')->default('active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_wage_type');
    }
};
