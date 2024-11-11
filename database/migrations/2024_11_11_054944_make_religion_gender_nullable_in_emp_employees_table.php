<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeReligionGenderNullableInEmpEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_employees', function (Blueprint $table) {
            // Make religion and gender fields nullable
            $table->string('religion')->nullable()->change();
            $table->string('gender')->nullable()->change();
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
            // Revert religion and gender fields to non-nullable
            $table->string('religion')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
        });
    }
}
