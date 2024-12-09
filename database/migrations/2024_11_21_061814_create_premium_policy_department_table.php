<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('premium_policy_department', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedInteger('premium_policy_id'); // Foreign key to premium_policy table
            $table->unsignedInteger('department_id'); // Foreign key to department table

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
        Schema::dropIfExists('premium_policy_department');
    }
};
