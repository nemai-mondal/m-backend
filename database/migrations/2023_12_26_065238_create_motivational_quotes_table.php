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
        Schema::create('motivational_quotes', function (Blueprint $table) {
            $table->id();
            $table->string("quote", 10000);
            $table->string("said_by")->nullable();
            $table->foreignId("user_id")->constrained();
            $table->integer("status");
            $table->date('display_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivational_quotes');
    }
};
