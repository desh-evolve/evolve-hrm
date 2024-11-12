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
        Schema::create('exception_policy', function (Blueprint $table) {
            $table->id();
            $table->integer('exception_policy_control_id');
            $table->string('type_id', 3);
            $table->integer('severity_id');
            $table->integer('grace')->nullable();
            $table->integer('watch_window')->nullable();
            $table->integer('demerit')->nullable();
            $table->boolean('enable_authorization')->default(0);
            $table->integer('email_notification_id')->default(0);

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
        Schema::dropIfExists('exception_policy');
    }
};
