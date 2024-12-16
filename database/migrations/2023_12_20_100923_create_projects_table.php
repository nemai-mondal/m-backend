<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('estimation_value')->nullable();
            $table->string('estimation_type')->nullable();
            $table->string('cost')->nullable();
            $table->string('currency_type')->nullable();
            $table->string('project_type')->nullable();
            $table->string('project_status')->nullable();
            $table->string('priority')->nullable();
            $table->string('experience')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('no_of_openings')->nullable();
            $table->string('notice_period')->nullable();
            $table->longText('description')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
