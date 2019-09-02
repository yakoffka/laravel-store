<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('slug');
            $table->string('path');
            $table->string('name');
            $table->string('ext');
            $table->string('alt');
            $table->unsignedInteger('sort_order')->default(9);
            $table->string('orig_name');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // удаление директорий с изображениями!!!
        $directory = substr(config('imageyo.dirdst'), 4); // '/app/public/images/products' => '/public/images/products'
        // echo '    directory = "' . $directory . '"' . "\n";
        if ( Storage::deleteDirectory($directory) ) {
            echo '    deleted $directory = "' . $directory . '"' . "\n";
        } else {
            echo '    not deleted $directory = "' . $directory . '"' . "\n"; 
        };

        $directory = substr(config('imageyo.dirdst_origin'), 4); // '/app/public/images/products' => '/public/images/products'
        // echo '    directory = "' . $directory . '"' . "\n";
        if ( Storage::deleteDirectory($directory) ) {
            echo '    deleted $directory = "' . $directory . '"' . "\n";
        } else {
            echo '    not deleted $directory = "' . $directory . '"' . "\n"; 
        };


        Schema::dropIfExists('images');
    }
}
