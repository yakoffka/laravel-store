<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->unique();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('total_qty');
            $table->unsignedInteger('total_payment');
            $table->text('cart');
            $table->unsignedInteger('status_id');
            $table->text('comment')->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')
            //     ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
