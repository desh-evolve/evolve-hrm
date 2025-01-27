<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_period_time_sheet_verify', function (Blueprint $table) {
            // Drop the existing status column if it exists
            if (Schema::hasColumn('pay_period_time_sheet_verify', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pay_period_time_sheet_verify', 'status_id')) {
                $table->dropColumn('status_id');
            }

            // Add a new 'status' column with a default value and comment
            $table->string('status', 50)
                  ->after('user_verified_date') // Adjust based on table structure
                  ->default('pending_authorization')
                  ->comment('incomplete(10), open(20), pending_authorization(30), authorization_open(40), pending_employee_verification(45), verified(50), authorization_declined(55), disabled(60)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pay_period_time_sheet_verify', function (Blueprint $table) {
            // Drop the 'status' column
            if (Schema::hasColumn('pay_period_time_sheet_verify', 'status')) {
                $table->dropColumn('status');
            }

            // Add back the old column
            $table->integer('status')->nullable();
        });
    }
};
