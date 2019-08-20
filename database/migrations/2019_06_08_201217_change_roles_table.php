<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding in table for storing roles
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedInteger('rank')->unique()->after('description'); // субординация ролей
            $table->boolean('is_basic')->default(false)->after('rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function(Blueprint $table)
        {
            $table->dropColumn('rank');
            $table->dropColumn('is_basic');
        });
    }
}
