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
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string('title')->nullable();
            $table->string('name')->nullable();;
            $table->string('gender')->nullable();
            $table->string('relation')->nullable();;
            $table->string('address')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('contact_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('marriage_date')->nullable();
            $table->string('maritial_status')->nullable();
            $table->string('employment')->nullable();
            $table->string('proffesion')->nullable(); 
            $table->string('nationality')->nullable();
            $table->string('insurance_name')->nullable();
            $table->string('remarks')->nullable();
            $table->string('is_depend')->nullable();
            $table->string('health_insurance')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_families');
    }
};
