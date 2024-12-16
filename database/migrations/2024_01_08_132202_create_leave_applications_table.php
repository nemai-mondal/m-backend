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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("leave_type_id");
            $table->date("leave_from");
            $table->date("leave_to");
            $table->string("leave_value_start");
            $table->string("leave_value_end");
            $table->float('total_days');
            $table->string("leave_status");
            $table->string("remarks")->nullable();
            $table->string("attachment")->nullable();
            $table->string("email_notification_to")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
