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
        Schema::create('exception', function (Blueprint $table) {
            $table->id();
            $table->integer('user_date_id');
            $table->integer('exception_policy_id');
            $table->integer('punch_id')->nullable();
            $table->integer('punch_control_id')->nullable();
            $table->integer('type_id');
            $table->boolean('enable_demerit')->default(0);
            $table->boolean('authorized')->default(0);

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
        Schema::dropIfExists('exception');
    }
};
