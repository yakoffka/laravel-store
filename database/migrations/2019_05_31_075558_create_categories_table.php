<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->default(Str::uuid()); // for naming source category
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('imagepath')->nullable()->charset('utf8');
            $table->boolean('publish')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('added_by_user_id');
            $table->unsignedInteger('edited_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
