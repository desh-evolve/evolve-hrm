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
        Schema::table('object_type', function (Blueprint $table) {
            // Adding new columns after the 'gender' column
            $table->string('type', 25)->after('id')->nullable()->comment('request/other/email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('object_type', function (Blueprint $table) {
            // Dropping the new columns
            $table->dropColumn('type');
        });
    }

};
