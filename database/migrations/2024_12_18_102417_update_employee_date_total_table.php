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
        Schema::table('employee_date_total', function (Blueprint $table) {
            // Drop the column if it already exists
            if (Schema::hasColumn('employee_date_total', 'punch_status')) {
                $table->dropColumn('punch_status');
            }
            if (Schema::hasColumn('employee_date_total', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('employee_date_total', 'punch_type')) {
                $table->dropColumn('punch_type');
            }

            // Add a new 'status' column with a default value
            $table->string('type', 25)
                  ->after('comment_ot')
                  ->default('total')
                  ->comment('total/regular/overtime/premium/lunch/break');
            $table->string('status', 25)
                  ->after('type')
                  ->default('system')
                  ->comment('system/worked/absence/delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_date_total', function (Blueprint $table) {
            // Drop the 'status' and 'type' columns added in the up method
            if (Schema::hasColumn('employee_date_total', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('employee_date_total', 'type')) {
                $table->dropColumn('type');
            }

            // Re-add the old columns removed in the up method
            if (!Schema::hasColumn('employee_date_total', 'punch_status')) {
                $table->string('punch_status', 50)->nullable();
            }
            if (!Schema::hasColumn('employee_date_total', 'punch_type')) {
                $table->string('punch_type', 50)->nullable();
            }
            if (!Schema::hasColumn('employee_date_total', 'status')) {
                $table->integer('status')->nullable();
            }
        });
    }

};
