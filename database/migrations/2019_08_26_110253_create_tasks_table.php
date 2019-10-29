<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('master_user_id');
            $table->unsignedBigInteger('slave_user_id');
            $table->string('name')->unique();
            // $table->string('slug');
            $table->text('description');
            $table->unsignedBigInteger('tasksstatus_id');
            $table->unsignedBigInteger('taskspriority_id');
            $table->text('comment_slave')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('added_by_user_id');
            $table->unsignedBigInteger('edited_by_user_id')->nullable();
            $table->softDeletes();
        });
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
