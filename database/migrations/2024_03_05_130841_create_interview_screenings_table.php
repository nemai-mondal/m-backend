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
        Schema::create('interview_screenings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("interview_id");
            $table->string("work_exp_assessment")->nullable();
            $table->integer("interpersonal_skill_score")->nullable();
            $table->integer("communication_skill_score")->nullable();
            $table->string("attitude")->nullable();
            $table->boolean("is_suitable")->nullable();
            $table->string("status")->nullable();
            $table->string("remarks")->nullable();
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
        Schema::dropIfExists('interview_screenings');
    }
};
