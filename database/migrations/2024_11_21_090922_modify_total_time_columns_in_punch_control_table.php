<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('punch_control', function (Blueprint $table) {
            // Modify total_time column to TIME
            $table->time('total_time')->nullable()->change();
            
            // Modify actual_total_time column to TIME
            $table->time('actual_total_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('punch_control', function (Blueprint $table) {
            // Revert total_time back to INT
            $table->integer('total_time')->unsigned()->change();
            
            // Revert actual_total_time back to INT
            $table->integer('actual_total_time')->unsigned()->change();
        });
    }
};
