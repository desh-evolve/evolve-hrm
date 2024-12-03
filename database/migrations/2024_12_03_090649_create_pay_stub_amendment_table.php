<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_stub_amendment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('pay_stub_entry_name_id');
            $table->integer('effective_date')->nullable();
            $table->decimal('rate', 20, 4)->nullable();
            $table->decimal('units', 20, 4)->nullable();
            $table->decimal('amount', 20, 4)->nullable();
            $table->string('description', 250)->nullable();
            $table->boolean('authorized')->default(0);
            $table->unsignedInteger('recurring_ps_amendment_id')->nullable();
            $table->boolean('ytd_adjustment')->default(0);
            $table->unsignedInteger('type');
            $table->decimal('percent_amount', 20, 4)->nullable();
            $table->unsignedInteger('percent_amount_entry_name_id')->nullable();

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
        Schema::dropIfExists('pay_stub_amendment');
    }
};
