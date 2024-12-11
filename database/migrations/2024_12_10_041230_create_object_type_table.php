<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('object_type', function (Blueprint $table) { //for hierarchies
            $table->increments('id'); 
            $table->string('name', 100); 
            $table->string('status', 11)->default('active'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('object_type');
    }
};
