<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('projects')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->string('project_uuid')->nullable();
                $table->foreign('project_uuid')->references('uuid')->on('projects')->onDelete('cascade');
                $table->string('name');
                $table->text('description');
                $table->dateTime('due_date');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
