<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('employee_date_id'); //from employee date table
            $table->unsignedInteger('type_id'); 
            $table->unsignedInteger('status_id'); 
            $table->boolean('authorized')->default(0); 
            $table->smallInteger('authorization_level')->default(99); 

            // Audit fields
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();  
        });
    }

    public function down()
    {
        Schema::dropIfExists('request');
    }
};
