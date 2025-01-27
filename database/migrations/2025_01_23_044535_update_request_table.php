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
            // Drop the existing status column if it exists
            if (Schema::hasColumn('request', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('request', 'status_id')) {
                $table->dropColumn('status_id');
            }

            // Add a new 'status' column with a default value and comment
            $table->string('status', 25)
                  ->after('authorization_level') // Adjust based on table structure
                  ->default('pending')
                  ->comment('incomplete(10), open(20), pending(30), authorization_open(40), authorized(50), declined(55), disabled(60)');
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
