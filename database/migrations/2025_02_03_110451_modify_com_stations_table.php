<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('com_stations', function (Blueprint $table) {
            // Change station_customer_id to string
            $table->string('station_customer_id')->nullable()->change();

            // Change source to string
            $table->string('source')->nullable()->change();

            // Add department_id as unsignedBigInteger
            $table->unsignedBigInteger('department_id')->nullable()->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('com_stations', function (Blueprint $table) {
            // **Revert station_customer_id to original type safely**
            $table->string('station_customer_id', 36)->nullable()->change(); // Keeping as string (UUID-safe)
            
            // **Revert source to original type safely**
            $table->string('source')->nullable()->change(); // Keep as string instead of BigInt to avoid errors

            // Drop department_id
            $table->dropColumn('department_id');
        });
    }
};
