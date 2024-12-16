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
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->unsignedBigInteger("added_by_id")->nullable();
            // $table->unsignedBigInteger("employment_type_id")->nullable();
            // $table->unsignedBigInteger("department_id")->nullable();
            $table->string("description")->nullable();
            $table->integer("status")->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('added_by_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('employment_type_id')->references('id')->on('employment_types')->onDelete('cascade');
            // $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amendments');
    }
};
