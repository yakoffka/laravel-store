<?php

use App\User;
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
            $table->unsignedBigInteger('user_id')->default(User::URUID); // unregistered user id
            $table->set('model', [
                'categories',
                'comments',
                'images',
                'manufacturers',
                'orders',
                'products',
                'roles',
                'settings',
                'users',
                'tasks',
            ]);
            $table->unsignedBigInteger('model_id');
            $table->string('model_name');
            $table->set('type', [
                'verify',
                'created',
                'updated',
                'deleted',
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
