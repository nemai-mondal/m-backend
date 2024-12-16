<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_announcements', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->foreignId("user_id")->constrained();
            $table->integer("department_id");
            // $table->integer("designation_id");
            $table->date("event_date");
            $table->time("event_start_time");
            $table->time("event_end_time");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_announcements');
    }
};
