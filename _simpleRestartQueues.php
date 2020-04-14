<?php

$s = 'restartWithClean:';
// $php = 'php74'; // hosting
$php = 'php'; // vagrant machine

$commands = [
    $php . ' ' . __DIR__ . '/_killQueueWorker.php',
    $php . ' artisan queue:restart',
    $php . ' ' . __DIR__ . '/_watchdog_queue_larastore.php',
];

foreach ($commands as $command) {
    echo "$s call '$command'\n";
    sleep(1);
    echo "$s " . exec($command) . "\n\n";
}
