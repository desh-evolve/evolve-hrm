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
        Schema::table('pay_period_schedule_employee', function (Blueprint $table) {
            $table->string('status', 10)
                ->default('active')
                ->after('employee_id')
                ->comment('Status of the record (active/inactive)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_period_schedule_employee', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
