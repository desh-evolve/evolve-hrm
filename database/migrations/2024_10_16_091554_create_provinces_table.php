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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('province_name');
            $table->unsignedBigInteger('country_id')->nullable(); 
            $table->string('status')->nullable(); 
            $table->dateTime('created_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('deleted_date')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
