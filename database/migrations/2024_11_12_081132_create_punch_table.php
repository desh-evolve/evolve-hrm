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
            $table->string('punch_type', 11)->comment('normal(10), lunch(20), break(30)');
            $table->string('punch_status', 11)->comment('in(10), out(20)');
            $table->timestamp('time_stamp')->nullable()->comment('entered value');
            $table->timestamp('original_time_stamp')->nullable()->comment('when editing store original here');
            $table->timestamp('actual_time_stamp')->nullable()->comment('created at');

            $table->boolean('transfer')->default(0)->comment('no(0), yes(1)');
            $table->decimal('longitude', 15, 10)->nullable();
            $table->decimal('latitude', 15, 10)->nullable();

            $table->string('status')->default('active')->nullable();
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
        Schema::dropIfExists('punch');
    }
};
