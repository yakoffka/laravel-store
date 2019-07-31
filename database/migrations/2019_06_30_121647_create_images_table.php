<?php

use Illuminate\Support\Facades\Schema;
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

            // $table->foreign('product_id')->references('id')->on('products')
            //     // ->onUpdate('cascade')->onDelete('cascade')
            //     ;

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
        Schema::dropIfExists('images');
    }
}




/*
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint (
    SQL: alter table `images` add constraint `images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade on update cascade)

General error: 1215 Cannot add foreign key constraint (SQL: alter table `images` add constraint `images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade on update cascade)

SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'images' already exists (SQL: create table `images` (`id` bigint unsigned not null auto_increment primary key, `product_id` bigint not null, `slug` varchar(255) not null, `path` varchar(255) not null, `name` varchar(255) not null, `ext` varchar(255) not null, `alt` varchar(255) not null, `sort_order` int unsigned not null default '9', `orig_name` varchar(255) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci')

SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint (
    SQL: alter table `images` add constraint `images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade on update cascade)




SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint (SQL: alter table `comments` add constraint `comments_user_name_foreign` foreign key (`user_name`) references `users` (`name`) on delete cascade on update cascade)
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint (SQL: alter table `images` add constraint `images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade on update cascade)


*/