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

    // $php = 'php74'; // hosting
    $php = 'php'; // vagrant machine

    if (shell_exec('ps -ef|grep -v grep|grep "' . $php . ' ' . __DIR__ . '/artisan queue:work"') === null) {
        exec($php .' ' . __DIR__ . '/artisan queue:work > /dev/null &');
        exit('the worker started successfully' . "\n");
    }

    exit('the worker is already running' . "\n");
