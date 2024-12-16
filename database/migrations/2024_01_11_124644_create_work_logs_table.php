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
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("activity_id")->nullable();
            $table->unsignedBigInteger("project_id")->nullable();
            $table->unsignedBigInteger("client_id")->nullable();
            $table->unsignedBigInteger("department_id")->nullable();
            $table->date("date")->nullable();
            $table->time("time_spent")->nullable();
            $table->string("target")->nullable();  
            $table->string("description", 1500)->nullable();
            $table->string("task_url", 500)->nullable(); 
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
