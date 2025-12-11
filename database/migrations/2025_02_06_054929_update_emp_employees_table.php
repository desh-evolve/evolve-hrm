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
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->unsignedInteger('default_branch_id')->nullable()->after('pay_period_id')->default(1);
            $table->unsignedInteger('default_department_id')->nullable()->after('default_branch_id')->default(1);
            $table->string('employee_number')->nullable()->after('default_department_id')->comment('if needed later use this otherwise you can use emp_employees table id as employee number');
            
            // Add comments to existing columns
            $table->string('title')->comment('mr(10), mrs(20), miss(30), hons(40)')->change();;
            $table->string('religion')->nullable()->comment('buddhist(10), christian(20), tamil(30), muslim(40), none, other')->change();;
            $table->string('gender')->nullable()->comment('unspecified(5), male(10), female(20)')->change();;
            $table->string('marital_status')->nullable()->comment('unspecified(5), single(10), married(20)')->change();;
            $table->string('status')->comment('active(10), Leave - Illness/Injury(12), Leave - Maternity/Parental(14), Leave - Other(16), Terminated(20)')->change();;
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_employees', function (Blueprint $table) {
            $table->dropColumn('default_branch_id');
            $table->dropColumn('default_department_id');

            // Remove comments from existing columns
            $table->string('title')->comment(null)->change();;
            $table->string('religion')->nullable()->comment(null)->change();;
            $table->string('gender')->nullable()->comment(null)->change();;
            $table->string('marital_status')->nullable()->comment(null)->change();;
            $table->string('status')->comment(null)->change();;
         
        });
    }
};
