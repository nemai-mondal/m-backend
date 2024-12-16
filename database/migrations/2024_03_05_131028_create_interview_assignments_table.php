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
        Schema::create('interview_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("interview_id")->nullable();
            $table->string("name")->nullable();
            $table->string("details")->nullable();
            $table->date("assignment_date")->nullable();
            $table->date("submission_date")->nullable();
            $table->string("remarks")->nullable();
            $table->string("status")->nullable();
            $table->string("interview_round")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_assignments');
    }
};
