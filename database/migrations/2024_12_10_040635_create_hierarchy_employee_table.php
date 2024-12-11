<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hierarchy_employee', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedInteger('hierarchy_control_id'); 
            $table->unsignedInteger('employee_id'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('hierarchy_employee');
    }
};

