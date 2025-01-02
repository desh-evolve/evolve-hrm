<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('policy_group_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('policy_group_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);

            // Audit fields
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_group_users');
    }
};

