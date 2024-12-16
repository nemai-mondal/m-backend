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
        Schema::create('attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_assign_id')->nullable(); //user id from user table
            $table->unsignedBigInteger('user_id')->nullable(); //user id from user table

            $table->unsignedBigInteger('shift_id')->nullable(); //shift id from shifts table
            $table->string('shift_start_time')->nullable(); //shift start from shifts table
            $table->string('shift_end_time')->nullable(); //shift end from shifts table

            $table->time('user_login_time')->nullable(); //employee login time
            $table->time('user_logout_time')->nullable(); //employee logout time
            $table->boolean('late_checking')->nullable(); // 1 or 0
            $table->boolean('early_checkout')->nullable(); // 1 or 0

            $table->time('login_duration')->nullable(); // Total hours, from login to logout, including breaks
            $table->time('work_duration')->nullable(); // Total working hours, excluding breaks
            $table->time('break_duration')->nullable(); // Total working hours, excluding breaks

            $table->string('login_remarks')->nullable(); // Early login, late login
            $table->string('logout_remarks')->nullable(); // Early logout, late logout

            $table->string('user_work')->nullable(); // Half day, full day
            $table->string('absent_reason')->nullable(); // Weekend, holiday, leave, working day
            $table->string('absent_value')->nullable(); // Sunday, Dipawali, Sick Leave, Monday
            $table->unsignedBigInteger('leave_application_id')->nullable(); //leave application id from leave application table

            $table->boolean('is_regularized')->nullable(); // if regularized then 1 else 0 or null
            $table->unsignedBigInteger('regularization_requested_by')->nullable(); // employee id who regularized/approve ex - 2
            $table->unsignedBigInteger('regularization_approved_by')->nullable(); // employee id who regularized/approve ex - 2
            $table->date('regularization_date')->nullable(); // Employee login is late because office internet was down
            $table->string('regularization_remarks')->nullable(); // Employee login is late because office internet was down
            
            $table->string('processing_status')->nullable(); // Employee login is late because office internet was down
            $table->string('processing_remarks')->nullable(); // Employee login is late because office internet was down


            // $table->date('ar_date')->nullable(); // Arrival date
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attendance_assign_id')->references('id')->on('attendance_assigns')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_application_id')->references('id')->on('leave_applications')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('regularization_requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('regularization_approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_reports');
    }
};
