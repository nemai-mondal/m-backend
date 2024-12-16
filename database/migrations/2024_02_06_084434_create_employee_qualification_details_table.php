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
        Schema::create('employee_qualification_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string('qualification')->nullable();
            $table->string('stream_type')->nullable();;
            $table->string('qualification_course_type')->nullable();;
            $table->string('specialization')->nullable();
            $table->string('nature_of_course')->nullable();
            $table->string('qualification_status')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('university_name')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->date('date_of_passing')->nullable();
            $table->float('percentage')->nullable();
            $table->string('grade')->nullable();
            $table->string('duration_of_course')->nullable();
            $table->string('year')->nullable();
            $table->string('remarks')->nullable();
            $table->string('is_highest_qualification')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_qualification_details');
    }
};
