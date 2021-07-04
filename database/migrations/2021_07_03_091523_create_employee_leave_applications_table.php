<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('is_emergency')->default(0)->comment('Yes=1, No=0');
            $table->tinyInteger('leave_type')->default(1)->comment('Casual=1, Sick=2');
            $table->longText('subject')->nullable();
            $table->longText('message')->nullable();
            $table->longText('attached_files')->nullable();
            $table->string('reply_message')->nullable();
            $table->tinyInteger('status')->default(0)->comment('Pending=0, Approved=1, Reject=2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_leave_applications');
    }
}
