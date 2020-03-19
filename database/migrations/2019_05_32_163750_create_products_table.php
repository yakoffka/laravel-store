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
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(5);
            $table->unsignedInteger('manufacturer_id')->nullable();
            $table->string('vendor_code')->nullable();
            $table->string('code_1c')->nullable();
            $table->unsignedInteger('category_id');
            $table->boolean('publish')->default(false);
            $table->string('materials')->nullable();
            $table->text('description')->nullable();
            $table->text('modification')->nullable();
            $table->text('workingconditions')->nullable();
            $table->string('date_manufactured', 10)->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->float('promotional_price', 8, 2)->nullable();
            $table->unsignedInteger('promotional_percentage')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('diameter')->nullable();
            $table->unsignedInteger('remaining')->nullable();
            $table->unsignedInteger('added_by_user_id');
            $table->unsignedInteger('edited_by_user_id')->nullable();
            $table->unsignedInteger('count_views')->default(0); // кол-во просмотров товара
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
