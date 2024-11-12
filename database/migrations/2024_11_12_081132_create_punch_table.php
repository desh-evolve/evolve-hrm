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
        Schema::create('punch', function (Blueprint $table) {
            $table->id();
            $table->integer('punch_control_id');
            $table->integer('station_id')->nullable();
            $table->string('punch_type', 11)->comment('normal/lunch/break');
            $table->string('punch_status', 11)->comment('in/out');
            $table->timestamp('time_stamp')->nullable()->comment('entered value');
            $table->timestamp('original_time_stamp')->nullable()->comment('when editing store original here');
            $table->timestamp('actual_time_stamp')->nullable()->comment('created at');

            $table->boolean('transfer')->default(0);
            $table->decimal('longitude', 15, 10)->nullable();
            $table->decimal('latitude', 15, 10)->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();

            $table->index('punch_control_id', 'punch_punch_control_id');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punch');
    }
};
