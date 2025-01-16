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
        Schema::create('absence_leave_user', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing primary key
            $table->string('name', 50); // Name of the record
            $table->unsignedInteger('absence_leave_id'); // Foreign key to absence_leave
            $table->unsignedInteger('absence_policy_id'); // Foreign key to absence_policy
            $table->decimal('amount', 18, 4); // Amount
            $table->string('leave_date_year', 11); // Leave year
            $table->unsignedSmallInteger('basis_employment'); // Basis of employment
            $table->unsignedSmallInteger('leave_applicable'); // Leave applicability
            $table->decimal('minimum_length_of_service', 11, 4); // Min service length
            $table->unsignedSmallInteger('minimum_length_of_service_unit_id'); // Min service unit
            $table->decimal('maximum_length_of_service', 11, 4); // Max service length
            $table->unsignedSmallInteger('maximum_length_of_service_unit_id'); // Max service unit

            // Audit fields
            $table->string('status')->default('active')->comment('active/delete')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_leave_user');
    }
};
