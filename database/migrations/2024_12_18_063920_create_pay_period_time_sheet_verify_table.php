<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_period_time_sheet_verify', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('pay_period_id'); 
            $table->unsignedInteger('user_id'); 
            $table->unsignedInteger('status_id'); 
            $table->boolean('authorized')->default(0); 
            $table->smallInteger('authorization_level')->default(99); 
            $table->smallInteger('user_verified')->default(0); 
            $table->integer('user_verified_date')->nullable(); 

            // Audit fields
            $table->string('status', 25)->default('active')->comment('active/delete');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('pay_period_time_sheet_verify');
    }
};
