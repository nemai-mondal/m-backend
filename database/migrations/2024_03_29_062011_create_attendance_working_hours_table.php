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
        Schema::create('attendance_working_hours', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('working_hours_calculation')->nullable();
            $table->string('total_hours')->nullable();
            $table->string('full_day_hours')->nullable();
            $table->string('half_day_hours')->nullable();
            $table->string('grace_for_checkin')->nullable();
            $table->string('grace_for_checkout')->nullable();
            $table->boolean('late_checkin_allowed')->nullable();
            $table->string('late_checkin_count')->nullable();
            $table->string('late_checkin_frequency')->nullable();
            $table->string('late_checkin_minutes')->nullable();
            $table->boolean('early_checkout_allowed')->nullable();
            $table->string('early_checkout_count')->nullable();
            $table->string('early_checkout_frequency')->nullable();
            $table->string('early_checkout_minutes')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_working_hours');
    }
};
