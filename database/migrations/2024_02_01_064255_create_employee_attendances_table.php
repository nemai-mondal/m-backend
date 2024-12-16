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
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('punch_required')->nullable();
            $table->boolean('cc_not_allowed')->nullable();
            $table->boolean('single_punch_required')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->integer('overtime_default')->nullable();
            $table->integer('overtime_weekoff')->nullable();
            $table->integer('overtime_holiday')->nullable();
            $table->date('weekoff_start_default')->nullable();
            $table->integer('weekoff_start_approved')->nullable();
            $table->timestamps();
            $table->softDeletes(); 

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
