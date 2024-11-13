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
        Schema::table('exception_policy', function (Blueprint $table) {
            // Remove columns severity_id and email_notification_id
        $table->dropColumn('severity_id');
        $table->dropColumn('email_notification_id');

        // Add severity as a string with a default value and comment
        $table->string('severity')->default('low')->comment('low/medium/high/critical')->after('demerit');

        // Modify grace and watch_window to include comments (if not already present)
        $table->integer('grace')->nullable()->comment('time in seconds')->change();
        $table->integer('watch_window')->nullable()->comment('time in seconds')->change();

        // Add new email_notification as a string with default and comment
        $table->string('email_notification')->default('both')->comment('none/employee/supervisor/both')->after('severity');

        // Add new 'active' column after 'demerit'
        $table->boolean('active')->default(0)->after('demerit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exception_policy', function (Blueprint $table) {
            // Revert by adding severity_id and email_notification_id back as integers
            $table->integer('severity_id');
            $table->integer('email_notification_id')->default(0);

            // Remove the new severity and email_notification columns
            $table->dropColumn('severity');
            $table->dropColumn('email_notification');
            
            // Remove the new active column
            $table->dropColumn('active');

            // Remove comments from grace and watch_window (if needed)
            $table->integer('grace')->nullable()->change();
            $table->integer('watch_window')->nullable()->change();
        });
    }
};
