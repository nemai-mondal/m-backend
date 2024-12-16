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
        Schema::create('employee_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('address_type')->nullable();
            $table->string('line1')->nullable();
            $table->string('line2')->nullable(); 
            $table->string('line3')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string("city_type")->nullable();
            $table->string("phone1")->nullable();
            $table->string("phone2")->nullable();
            $table->string("landline1")->nullable();
            $table->string("landline2")->nullable();
            $table->string("contact_name")->nullable();
            $table->string("relation")->nullable();
            $table->date("wef")->nullable();
            $table->boolean("permanent_same_as_current")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_permanent_addresses');
    }
};
