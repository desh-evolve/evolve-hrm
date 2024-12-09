<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_period_schedule_employee', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->unsignedInteger('pay_period_schedule_id')->default(0); // Foreign key for pay period schedule
            $table->unsignedInteger('employee_id')->default(0); // Foreign key for employee

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pay_period_schedule_employee');
    }
};
