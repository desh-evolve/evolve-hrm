<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('absence_policy', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->integer('company_id')->unsigned();
            $table->string('name', 250);
            $table->string('type')->nullable()->comment('paid(10), paid_above_salary(12), unpaid(20), dock(30)');
            $table->boolean('over_time')->default(0);
            $table->integer('accrual_policy_id')->unsigned()->nullable();
            $table->integer('premium_policy_id')->unsigned()->nullable();
            $table->integer('pay_stub_entry_account_id')->unsigned()->nullable();
            $table->integer('wage_group_id')->unsigned()->default(0);
            $table->decimal('rate', 9, 4)->nullable();
            $table->decimal('accrual_rate', 9, 4)->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absence_policy');
    }
};
