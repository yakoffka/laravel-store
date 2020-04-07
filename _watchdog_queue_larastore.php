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

$s = 'Watchdog: ';
// $php = 'php74'; // hosting
$php = 'php'; // vagrant machine

$command = $php . ' ' . __DIR__ . '/artisan queue:work --queue=high,low';

runWorker($s, $command);

function runWorker(string $s, string $command, int $i = 1)
{
    $pid = shell_exec('ps -ef|grep -v grep|grep "' . $command . '"');
    if ($pid === null) {
        echo $s . 'trying to run a worker. ' . "$i\n";
        exec($command . ' > /dev/null &');
        sleep(3);
        if ($i < 3) {
            runWorker($s, $command, ++$i);
        }
        exit($s . 'abort. maximum number of attempts reached' . "\n");
    }

    if ($i > 1) {
        exit($s . 'the worker started successfully' . "\n");
    }
    exit($s . 'the worker is already running' . "\n");
}
