<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoleIdFromEmpEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->dropColumn('role_id');  // Remove the role_id column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->integer('role_id')->nullable()->after('pay_period_id'); // Add the column back
        });
    }
}
