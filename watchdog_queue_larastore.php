<?php

    /*
     * in crontab:
     *     MAILTO="recipient@mail.ru"
     *     25 09 * * * php74 /path/to/watchdog_queue_larastore.php
     *     MAILTO=""
     *     00 * * * * php74 /path/to/watchdog_queue_larastore.php
     *     20 * * * * php74 /path/to/watchdog_queue_larastore.php
     *     40 * * * * php74 /path/to/watchdog_queue_larastore.php
     */
    if (is_null(shell_exec('ps -ef|grep -v grep|grep "php74 ' . __DIR__ . '/artisan queue:work"'))) {
        exec('php74 ' . __DIR__ . '/artisan queue:work > /dev/null &');
        exit('the worker started successfully' . "\n");
    } else {
        exit('the worker is already running' . "\n");
    }
