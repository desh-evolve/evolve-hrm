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
        Schema::table('pay_stub_amendment', function (Blueprint $table) {
            // Change effective_date from int to DATE type
            $table->date('effective_date')->nullable()->change();

            // Change type from int to VARCHAR
            $table->string('type', 255)->nullable()->change();

            // Allow NULL for percent_amount_entry_name_id
            $table->unsignedInteger('percent_amount_entry_name_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pay_stub_amendment', function (Blueprint $table) {
            // Revert effective_date back to INT
            $table->integer('effective_date')->nullable()->change();

            // Revert type back to INT
            $table->unsignedInteger('type')->change();

            // Revert percent_amount_entry_name_id to NOT NULL
            $table->unsignedInteger('percent_amount_entry_name_id')->change();
        });
    }
};
