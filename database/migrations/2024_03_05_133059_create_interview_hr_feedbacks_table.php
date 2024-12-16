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
        Schema::create('interview_hr_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("interview_id")->nullable();
            $table->date("interview_date")->nullable();
            $table->integer("cultural_fit_assessment")->nullable();
            $table->integer("overall_assessment")->nullable();
            $table->date("joining_date")->nullable();
            $table->string("status")->nullable();
            $table->string("strength")->nullable();
            $table->string("weakness")->nullable();
            $table->string("feedback")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_hr_feedbacks');
    }
};
