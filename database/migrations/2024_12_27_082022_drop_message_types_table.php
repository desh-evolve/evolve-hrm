<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::dropIfExists('message_types'); //now we use object types table to store msg types
    }

    public function down()
    {
        Schema::create('message_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status', 11)->default('active');
        });
    }

};
