<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('emp_preference', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('user_id'); 
            $table->unsignedInteger('employee_id'); 
            $table->string('date_format', 250); 
            $table->string('time_format', 250); 
            $table->string('time_unit_format', 250);
            $table->string('time_zone', 250); 
            $table->unsignedInteger('items_per_page')->nullable(); 
            $table->unsignedInteger('timesheet_view')->nullable(); 
            $table->unsignedInteger('start_week_day')->nullable(); 
            $table->string('language', 5)->nullable(); 
            $table->boolean('enable_email_notification_exception')->default(0); 
            $table->boolean('enable_email_notification_message')->default(0); 
            $table->boolean('enable_email_notification_home')->default(0); 
            
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
        Schema::dropIfExists('emp_preference');
    }
};
