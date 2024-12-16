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
        Schema::create('employee_joinings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('office_email');
            $table->date('date_of_joining')->nullable();
            $table->date('salary_start_date')->nullable();
            $table->date('transfer_date')->nullable();
            $table->integer('probation_period_in_days')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->date('last_working_date')->nullable();
            $table->integer('notice_period_employer')->nullable();
            $table->integer('notice_period_employee')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_joinings');
    }
};
