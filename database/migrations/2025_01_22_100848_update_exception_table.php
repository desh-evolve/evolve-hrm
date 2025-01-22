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
        Schema::table('exception', function (Blueprint $table) {
            // Drop the 'type_id' column if it exists
            if (Schema::hasColumn('exception', 'type_id')) {
                $table->dropColumn('type_id');
            }

            // Add a new 'type' column (varchar(30)) with a default value
            $table->string('type', 30)
                  ->default('pending_authorization') // Default type
                  ->after('punch_control_id') 
                  ->comment('pre_mature(5), pending_authorization(30), authorization_open(40), active(50), authorization_declined(55), disabled(60), corrected(70)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exception', function (Blueprint $table) {
            // Drop the 'type' column
            if (Schema::hasColumn('exception', 'type')) {
                $table->dropColumn('type');
            }

            // Add back the old 'type_id' column (if it was an integer)
            $table->integer('type_id')->nullable();
        });
    }
};
