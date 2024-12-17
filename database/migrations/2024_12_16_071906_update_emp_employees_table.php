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
        Schema::table('emp_employees', function (Blueprint $table) {
            // Corrected column type: integer instead of int
            $table->integer('company_id')->after('id')->default(1);  // Default value is 1
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
            $table->dropColumn('company_id');
        });
    }

};
