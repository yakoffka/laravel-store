<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->set('type', [ // SET equivalent column.
                'category', 
                'comment',
                'image',
                'manufacturer', 
                'order', 
                'product', 
                'user', 
                'setting',
            ]);
            $table->unsignedBigInteger('type_id');
            $table->set('action', [ // SET equivalent column.
                'create', 
                'update', 
                'delete', 
            ]);
            $table->string('description', 200);
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
