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
        Schema::create('com_stations', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('branch_id'); 
            $table->unsignedBigInteger('station_type_id'); 
            $table->unsignedBigInteger('station_customer_id')->nullable(); 
            $table->unsignedBigInteger('source')->nullable(); 
            $table->string('description')->nullable(); 
            $table->string('time_zone')->nullable(); 
            
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
        Schema::dropIfExists('com_stations');
    }
};
