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
            $table->unsignedBigInteger('user_id')->default(7); // default unregistered user
            $table->set('model', [ // name model. set equivalent column.
                'Category', 
                'Comment',
                'Image',
                'Manufacturer',
                'Order',
                'Product',
                'Role',
                'User',
                'Setting',
            ]);
            $table->unsignedBigInteger('type_id');
            $table->set('type', [ // SET equivalent column.
                'model_create', 
                'model_update', 
                'model_delete',
                'model_copy',
                // 'verify', 
            ]);
            $table->text('description');
            $table->text('details')->nullable(); // serialized array. or longText??? or mediumText???
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
