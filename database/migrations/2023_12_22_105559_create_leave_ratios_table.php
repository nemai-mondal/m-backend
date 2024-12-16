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
        Schema::create('leave_ratios', function (Blueprint $table) {
            $table->id();
            $table->foreignId("employment_type_id")->constrained();
            $table->foreignId("leave_type_id")->constrained();
            $table->float('leave_credit');
            $table->string('frequency');
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_ratios');
    }
};
