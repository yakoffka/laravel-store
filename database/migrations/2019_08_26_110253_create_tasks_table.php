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
        // $statuses = array_column(config('task.statuses'), 'name');
        // $priorities = array_column(config('task.priorities'), 'name');

        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('master_user_id');
            $table->unsignedBigInteger('slave_user_id');
            $table->string('title', 100)->nullable();
            $table->string('slug');
            $table->text('description');

            // $table->set('status', [
            //     'opened',
            //     'done',
            //     'prorogue',
            //     'reopened',
            //     'closed',
            // ]);
            // $table->set('priority', [
            //     'important and urgent',
            //     'not important and urgent',
            //     'important and not urgent',
            //     'not important and not urgent',
            // ]);

            // $table->set('status', $statuses);
            // $table->set('priority', $priorities);

            $table->set('status', array_column(config('task.statuses'), 'name'));
            $table->set('priority', array_column(config('task.priorities'), 'name'));
            $table->text('comment_slave')->nullable();
            $table->timestamps();
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
