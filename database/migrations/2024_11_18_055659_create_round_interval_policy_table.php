<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('round_interval_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->integer('punch_type_id')->unsigned();
            $table->string('round_type')->nullable()->comment('down/average/up');
            $table->integer('round_interval')->unsigned()->comment('time in seconds');
            $table->boolean('strict')->default(0);
            $table->integer('grace')->nullable()->comment('time in seconds');
            $table->integer('minimum')->nullable();
            $table->integer('maximum')->nullable();
            
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('round_interval_policy');
    }
};

