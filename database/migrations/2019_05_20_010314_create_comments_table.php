<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing comments
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->string('user_name');
            $table->text('comment_string');
            $table->timestamps();


            // $table->foreign('product_id')->references('id')->on('products')
            //     ->onUpdate('cascade')->onDelete('cascade');

            // $table->foreign('user_name')->references('name')->on('products')
            //     ->onUpdate('cascade')->onDelete('cascade');

            // $table->foreign('user_name')->references('name')->on('users')
            //     ->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('user_name')->references('name')->on('users')
            //     ->onUpdate('cascade');
        });

        // second variant
        // Schema::table('comments', function ($table) {
        //     $table->foreign('user_name')->references('name')->on('users')
        //         ->onUpdate('cascade')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
