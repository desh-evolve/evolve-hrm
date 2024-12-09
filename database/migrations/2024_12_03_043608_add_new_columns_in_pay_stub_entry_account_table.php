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
        Schema::table('pay_stub_entry_account', function (Blueprint $table) {
            $table->string('active')->nullable(); // Or 'module', 'category' as needed
            $table->string('type', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_stub_entry_account', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->integer('type')->change();
        });
    }
};
