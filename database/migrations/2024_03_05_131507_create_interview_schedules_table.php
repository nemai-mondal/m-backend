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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("interview_id")->nullable();
            $table->string("interview_mode")->nullable();
            $table->date("interview_date")->nullable();
            $table->time("interview_time")->nullable();
            $table->string("interview_duration")->nullable();
            $table->string("interview_platform")->nullable();
            $table->string("interview_url")->nullable();
            $table->string("interview_agenda")->nullable();
            $table->string("assignment_given")->nullable();
            $table->unsignedBigInteger("assignment_id")->nullable();
            $table->unsignedBigInteger("related_to")->nullable();
            $table->string("reminder")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            // $table->foreign('related_to')->references('id')->on('interview_assignments')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('interview_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
