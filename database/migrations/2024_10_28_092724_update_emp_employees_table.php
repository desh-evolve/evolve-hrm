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
            // Adding new columns after the 'gender' column
            $table->string('bond_period')->after('gender');
            $table->integer('user_status')->after('bond_period');
            
            // Modifying 'religion' column from string to integer
            $table->integer('religion')->change();
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
            // Dropping the new columns
            $table->dropColumn('bond_period');
            $table->dropColumn('user_status');
            
            // Reverting 'religion' column back to string
            $table->string('religion')->change();
        });
    }

};
