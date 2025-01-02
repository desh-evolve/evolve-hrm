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
        if (!Schema::hasTable('message_users')) {
            Schema::create('message_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id')->index(); // Foreign key to messages table
                $table->unsignedBigInteger('receiver_id')->index(); // Foreign key for the receiver
                $table->tinyInteger('read_status')->default(0)->comment('0: not_read, 1: read');

                $table->string('status')->default('active');
                $table->timestamp('created_at')->useCurrent();
                $table->unsignedBigInteger('created_by')->default(0);
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
                $table->unsignedBigInteger('updated_by')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_users');
    }
};
