<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComBranchDepartmentUsersTable extends Migration
{
    public function up()
    {
        Schema::create('com_branch_department_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('br_dep_id');
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('user_id');
            
            $table->string('status')->default('active')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->default(0)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('updated_by')->default(0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('com_branch_department_users');
    }
}

