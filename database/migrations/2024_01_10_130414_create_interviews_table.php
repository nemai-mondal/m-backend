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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("applied_designation_id")->nullable();
            $table->unsignedBigInteger("applied_department_id")->nullable();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("source_name")->nullable();
            $table->string("source_link")->nullable();
            $table->string("total_experience")->nullable();
            $table->string("previous_designation")->nullable();
            $table->string("current_company")->nullable();
            $table->string("previous_company")->nullable();
            $table->string("current_ctc")->nullable();
            $table->string("expected_ctc")->nullable();
            $table->string("highest_qualification")->nullable();
            $table->string("notice_period")->nullable();
            $table->string("primary_skill")->nullable();
            $table->string("secondary_skill")->nullable();
            $table->string("remarks", 1000)->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('applied_designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('applied_department_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
