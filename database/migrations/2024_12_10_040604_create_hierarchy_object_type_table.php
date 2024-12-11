<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('hierarchy_object_type', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('hierarchy_control_id'); 
            $table->unsignedInteger('object_type_id'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('hierarchy_object_type');
    }
};
