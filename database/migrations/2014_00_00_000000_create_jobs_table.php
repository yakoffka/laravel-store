<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * renamed 2019_06_24_020521_create_jobs_table -> 2014_00_00_000000_create_jobs_table
     * [2020-03-31 20:57:05] local.ERROR: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'ls.jobs' doesn't exist (SQL: select * from `jobs` where `queue` = default and ((`reserved_at` is null and `available_at` <= 1585688225) or (`reserved_at` <= 1585688135)) order by `id` asc limit 1 for update) {"exception":"[object] (Illuminate\\Database\\QueryException(code: 42S02): SQLSTATE[42S02]: Base table or view not found: 1146 Table 'ls.jobs' doesn't exist (SQL: select * from `jobs` where `queue` = default and ((`reserved_at` is null and `available_at` <= 1585688225) or (`reserved_at` <= 1585688135)) order by `id` asc limit 1 for update) at /home/vagrant/projects/laravel_store/vendor/laravel/framework/src/Illuminate/Database/Connection.php:664, Doctrine\\DBAL\\Driver\\PDOException(code: 42S02): SQLSTATE[42S02]: Base table or view not found: 1146 Table 'ls.jobs' doesn't exist at /home/vagrant/projects/laravel_store/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:63, PDOException(code: 42S02): SQLSTATE[42S02]: Base table or view not found: 1146 Table 'ls.jobs' doesn't exist at /home/vagrant/projects/laravel_store/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:61)

     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
