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
        Schema::create('employee_personal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("father_name")->nullable();
            $table->string("mother_name")->nullable();
            $table->string("marital_status")->nullable();
            $table->date("marriage_date")->nullable();
            $table->string("spouse_name")->nullable();
            $table->string("personal_email")->nullable();
            $table->string("religion")->nullable();
            $table->string("nationality")->nullable();
            $table->string("country_of_birth")->nullable();
            $table->string("state_of_birth")->nullable();
            $table->string("place_of_birth")->nullable();
            $table->string("physical_disabilities")->nullable();
            $table->string("identification_mark1")->nullable();
            $table->string("identification_mark2")->nullable();
            $table->string("hobbies")->nullable();
            $table->string("confirmation_date")->nullable();
            $table->string("phone")->nullable();
            $table->date("date_of_birth")->nullable();
            $table->enum("gender", ['Male', 'Female', 'Other'])->nullable();
            // $table->string("adhaar")->nullable();
            // $table->string("pan")->nullable();
            $table->string("blood_group")->nullable();
            $table->string("alternate_number")->nullable();
            $table->string("emergency_number")->nullable();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_personal_details');
    }
};
