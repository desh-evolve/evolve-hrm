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
        Schema::create('allowance_data', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('payperiod_id');
            $table->integer('worked_days');
            $table->integer('late_days');
            $table->integer('nopay_days');
            $table->integer('fullday_leave_days');
            $table->integer('halfday_leave_days');

            $table->string('status')->default('active')->nullable()->comment('active, delete');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_data');
    }
};
