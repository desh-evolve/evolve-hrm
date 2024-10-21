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
        Schema::create('com_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('iso_code');
            $table->decimal('conversion_rate', 10, 4)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->decimal('previous_rate', 10, 4)->nullable();

            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_currencies');
    }
};
