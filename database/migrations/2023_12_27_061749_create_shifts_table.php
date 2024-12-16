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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->time("shift_start")->nullable();
            $table->time("shift_end")->nullable();
            $table->json("timezone")->nullable();
            $table->time("converted_shift_start")->nullable();
            $table->time("converted_shift_end")->nullable();
            $table->json("converted_timezone")->nullable();
            $table->string("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
