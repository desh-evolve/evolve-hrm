<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_period', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('company_id'); 
            $table->unsignedInteger('pay_period_schedule_id'); 
            $table->boolean('is_primary')->default(0); 
            $table->timestamp('start_date')->nullable(); 
            $table->timestamp('end_date')->nullable(); 
            $table->timestamp('transaction_date')->nullable(); 
            $table->timestamp('advance_end_date')->nullable(); 
            $table->timestamp('advance_transaction_date')->nullable(); 
            $table->boolean('tainted')->default(0); 
            $table->unsignedInteger('tainted_by')->nullable(); 
            $table->integer('tainted_date')->nullable(); 
            $table->unsignedInteger('is_hr_process')->default(0); 

            // Audit fields
            $table->string('status', 25)->default('open')->comment('open(10), locked(12), closed(20), post_adjustment(30)');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('pay_period');
    }
};
