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
        Schema::create('pay_stub', function (Blueprint $table) {
            $table->increments('id'); // Auto-increment primary key
            $table->unsignedInteger('pay_period_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->string('status', 25)->default('new')->comment('new(10), locked(20), open(25), pending_transaction(30), paid(40)');
            $table->date('status_date')->nullable();
            $table->unsignedInteger('status_by')->nullable();
            $table->boolean('advance')->default(0);
            
            // Audit fields
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
            
            // Date Fields
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('transaction_date')->nullable();

            // Additional Fields
            $table->boolean('tainted')->default(0);
            $table->boolean('temp')->default(0);
            $table->unsignedInteger('currency_id')->nullable();
            $table->decimal('currency_rate', 18, 10)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_stub');
    }
};
