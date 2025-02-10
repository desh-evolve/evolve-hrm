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
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->dropColumn('pay_period_id');  // Remove the pay_period_id column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->integer('pay_period_id')->nullable()->after('currency_id');
        });
    }
};
