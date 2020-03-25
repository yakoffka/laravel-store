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
            $table->string('name');       // basename
            $table->string('ext');        // extension
            $table->string('alt');        // === slug?
            $table->string('orig_name');  // original_name
            $table->unsignedInteger('sort_order')->default(9);
            $table->timestamps();

            /*@todo:
             * удалить alt;
             * переименовать поля
             * добавить l_name, s_name and m_name?
             */

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
        // @todo: добавить еще директории, вынести куда-нибудь.
        foreach ([
            '/public/images/products',
            '/uploads/images',
            '/public/images/manufacturers',
            '/public/images/categories',
            'public/lfm_img',
        ] as $directory) {
            if ( Storage::deleteDirectory($directory) ) {
                echo '    deleted $directory = "' . $directory . '"' . "\n";
            } else {
                echo '    not deleted $directory = "' . $directory . '"' . "\n";
            }
        }

        Schema::dropIfExists('images');
    }
}
