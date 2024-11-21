<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pay_stub_entry_account', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->integer('type')->unsigned()->comment('earning/employee_deduction/employer_deduction/total/accrual');
            $table->integer('ps_order')->unsigned();
            $table->string('name', 250);
            $table->integer('accrual_pay_stub_entry_account_id')->unsigned()->nullable();
            $table->string('debit_account', 250)->nullable();
            $table->string('credit_account', 250)->nullable();

            $table->string('status')->default('active')->nullable()->comment('active/disabled/delete');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pay_stub_entry_account');
    }
};
