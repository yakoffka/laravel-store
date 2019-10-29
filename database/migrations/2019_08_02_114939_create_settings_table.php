<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_group');
            $table->string('display_name');
            $table->text('description');
            $table->string('slug');
            $table->string('group');
            $table->string('type');
            $table->string('permissible_values', 600);
            $table->string('value')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
