<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createCustomeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customevents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(7); // default unregistered user
            $table->set('model', [ // name model. set equivalent column.
                // depricated
                'Category', 
                'Comment',
                'Image',
                'Manufacturer',
                'Order',
                'Product',
                'Role',
                'User',
                'Setting',

                // new values
                'categories', 
                'comments',
                'images',
                'manufacturers',
                'orders',
                'products',
                'roles',
                'users',
                'settings',
            ]);
            $table->unsignedBigInteger('model_id');
            $table->set('type', [ // SET equivalent column.
                // depricated
                'model_create', 
                'model_update', 
                'model_delete',
                'model_copy',

                // ??
                'verify',

                // new values
                'created',
                'updated',
                'deleted',
                // 'saved',
            ]);
            $table->text('description')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('customevents');
    }
}
