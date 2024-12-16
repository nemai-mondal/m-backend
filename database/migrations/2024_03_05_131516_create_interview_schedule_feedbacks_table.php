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
        Schema::create('interview_schedule_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("interview_id")->nullable();
            $table->unsignedBigInteger("interview_schedule_id")->nullable();
            $table->integer("code_quality")->nullable();
            $table->integer("problem_solving")->nullable();
            $table->string("status")->nullable();
            $table->integer("overall_rating")->require();
            $table->string("technical_feedback")->nullable();
            $table->string("additional_feedback")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->foreign('interview_schedule_id')->references('id')->on('interview_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedule_feedbacks');
    }
};
