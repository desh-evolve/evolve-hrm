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
        Schema::create('absence_leave_user_record', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->unsignedInteger('user_id'); // Foreign key to users
            $table->unsignedInteger('absence_policy_id'); // Foreign key to absence policies
            $table->unsignedInteger('absence_leave_id'); // Foreign key to absence leaves
            $table->unsignedInteger('absence_leave_user_id'); // Foreign key to absence leave users
            $table->unsignedBigInteger('user_date_id'); // User-specific date ID
            $table->timestamp('time_stamp')->nullable(); // Optional timestamp
            $table->decimal('amount', 18, 4)->nullable(); // Amount in decimal format

            $table->string('status')->default('active')->comment('active/delete')->nullable();
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
        Schema::dropIfExists('absence_leave_user_record');
    }
};
