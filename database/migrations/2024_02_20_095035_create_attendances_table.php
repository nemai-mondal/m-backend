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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); //user id from user table
            $table->unsignedBigInteger('shift_id')->nullable(); //shift id from shifts table
            $table->time('employee_login')->nullable(); //employee login time
            $table->time('employee_logout')->nullable(); //employee logout time
            $table->time('total_login_hours')->nullable(); // Total hours, from login to logout, including breaks
            $table->time('total_working_hours')->nullable(); // Total working hours, excluding breaks
            $table->string('login_remarks')->nullable(); // Early login, late login
            $table->string('logout_remarks')->nullable(); // Early logout, late logout
            $table->string('work_time')->nullable(); // Half day, full day, 
            $table->string('day_type')->nullable(); // Weekend, holiday, leave, working day
            $table->boolean('is_regularized')->nullable(); // if regularized then 1 else 0
            $table->unsignedBigInteger('regularized_by')->nullable(); // employee id who regularized/approve ex - 2
            $table->unsignedBigInteger('leave_application_id')->nullable(); //leave application id from leave application table
            $table->string('regularization_remarks')->nullable(); // Employee login is late because office internet was down
            $table->date('ar_date')->nullable(); // Arrival date
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_application_id')->references('id')->on('leave_applications')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('regularized_by')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
