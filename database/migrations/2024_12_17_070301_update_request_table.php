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
        Schema::table('request', function (Blueprint $table) {
            // Drop the column if it already exists
            if (Schema::hasColumn('request', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('request', 'status_id')) {
                $table->dropColumn('status_id');
            }

            // Add a new 'status' column with a default value
            $table->string('status', 25)
                  ->after('authorization_level')
                  ->default('pending')
                  ->comment('pending/authorized/delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request', function (Blueprint $table) {
            // Drop the 'status' column
            if (Schema::hasColumn('request', 'status')) {
                $table->dropColumn('status');
            }

            // Add back the old column
            $table->integer('status')->nullable();
        });
    }
};
