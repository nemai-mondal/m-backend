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
        Schema::create('event_wishes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("wish_from_id");
            $table->unsignedBigInteger("wish_to_id");
            $table->string("message");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wish_from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wish_to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_wishes');
    }
};
