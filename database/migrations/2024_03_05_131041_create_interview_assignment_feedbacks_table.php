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
        Schema::create('interview_assignment_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("interview_id")->nullable();
            $table->unsignedBigInteger("assignment_id")->nullable();
            $table->string("status")->nullable();
            $table->string("rating")->nullable();
            $table->string("overall_rating")->nullable();
            $table->string("feedback")->nullable();
            $table->date("feedback_submission_date")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('interview_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_assignment_feedbacks');
    }
};
