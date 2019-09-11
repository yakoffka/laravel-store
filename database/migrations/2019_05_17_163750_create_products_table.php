<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique(); // ??? how?
            $table->unsignedInteger('manufacturer_id')->nullable();
            $table->unsignedInteger('category_id');
            $table->boolean('visible');
            $table->string('materials')->nullable();
            $table->text('description')->nullable();
            $table->text('modification')->nullable(); // modification table
            $table->text('workingconditions')->nullable();
            $table->integer('year_manufacture')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->unsignedInteger('added_by_user_id');
            $table->unsignedInteger('edited_by_user_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
