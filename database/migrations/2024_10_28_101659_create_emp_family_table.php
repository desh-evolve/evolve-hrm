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
        Schema::create('emp_family', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->string('name');  // Family member's name
            $table->string('relationship');  // Relationship to the user
            $table->date('dob');  // Date of birth
            $table->string('nic')->nullable();  // National ID, nullable if not applicable
            $table->string('gender');  // Gender
            $table->string('contact_1')->nullable();  // Primary contact number
            $table->string('contact_2')->nullable();  // Secondary contact number, nullable
            $table->string('address_1')->nullable();  // Primary address
            $table->string('address_2')->nullable();  // Secondary address, nullable
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('emp_family');
    }
};
