<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emp_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title', 20);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name')->nullable();
            $table->string('name_with_initials')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('nic');
            $table->integer('country_id');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->string('postal_code')->nullable();
            $table->string('contact_1', 20);
            $table->string('contact_2', 20)->nullable();
            $table->string('work_contact', 20)->nullable();
            $table->string('home_contact', 20)->nullable();
            $table->string('immediate_contact_person')->nullable();
            $table->string('immediate_contact_no', 20)->nullable();
            $table->string('personal_email')->nullable();
            $table->string('work_email')->nullable();
            $table->string('epf_reg_no')->nullable();
            $table->string('religion')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->string('employee_image')->nullable();

            $table->integer('punch_machine_user_id')->nullable();
            $table->integer('designation_id');
            $table->integer('employee_group_id');
            $table->integer('policy_group_id');
            $table->date('appointment_date');
            $table->text('appointment_note')->nullable();
            $table->date('terminated_date')->nullable();
            $table->text('terminated_note')->nullable();
            $table->integer('employment_type_id');
            $table->integer('employment_time')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('resigned_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('pay_period_id')->nullable();
            $table->integer('role_id')->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_employees');
    }
};
