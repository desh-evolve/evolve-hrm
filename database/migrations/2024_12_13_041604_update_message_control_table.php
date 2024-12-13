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
        Schema::table('message_control', function (Blueprint $table) {
            $table->string('ref_type', 25)->default('email')->after('subject');
            $table->unsignedBigInteger('ref_id')->default(0)->after('ref_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_control', function (Blueprint $table) {
            // Dropping the new columns
            $table->dropColumn('ref_type');
            $table->dropColumn('ref_id');
        });
    }

};
