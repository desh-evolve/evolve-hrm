<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hierarchy_level', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedInteger('hierarchy_control_id'); 
            $table->integer('level'); 
            $table->unsignedInteger('user_id'); //user_id

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
        Schema::dropIfExists('hierarchy_level');
    }
};
