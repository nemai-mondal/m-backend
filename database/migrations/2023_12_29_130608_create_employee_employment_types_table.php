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
        Schema::create('employee_employment_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("employment_type_id");
            $table->integer("effective_for_days")->nullable();
            $table->date("effective_from")->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('employment_type_id')->references('id')->on('employment_types');
        

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_employment_types');
    }
};
