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

$s = 'restartWithClean:';
//// $php = 'php74'; // hosting
//$php = 'php'; // vagrant machine
//$command = $php . ' ' . __DIR__ . '/artisan queue:work --queue=high,low';
//
//killWorker($s, $command);
//
//function killWorker(string $s, string $command)
//{
//    $res = exec("pkill -f '$command'");
//    if (!$res) {
//        exit($s . 'the worker is killed' . "\n");
//    }
//}

$commands = [
    'php artisan migrate:refresh --seed',
    'php ' . __DIR__ . '/killQueueWorker.php',
    'php artisan queue:restart',
    'php ' . __DIR__ . '/watchdog_queue_larastore.php',
];

foreach ($commands as $command) {
    echo "$s call '$command'\n";
    sleep(1);
    echo "$s " . exec($command) . "\n\n";
}
