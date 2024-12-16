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
        Schema::create('attendance_regularizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('attendance_working_hour_id')->nullable();
            $table->boolean('future_days')->nullable();
            $table->boolean('past_days')->nullable();
            $table->boolean('current_day')->nullable();
            $table->string('after_days')->nullable();
            $table->string('past_month')->nullable();
            $table->string('before_salary')->nullable();
            $table->string('frequency')->nullable();
            $table->string('times')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attendance_working_hour_id')->references('id')->on('attendance_working_hours')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_regularizations');
    }
};
