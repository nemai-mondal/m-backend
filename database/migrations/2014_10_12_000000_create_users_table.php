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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('honorific')->nullable();
            $table->string('first_name')->nullable();;
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();;
            $table->string('employee_id')->unique();
            $table->string('email')->nullable();
            $table->boolean('status')->default(1);
            $table->string('password');
            $table->boolean('password_updated')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();


            $table->unique('email', 'unique_email_constraint')->whereNotNull('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
