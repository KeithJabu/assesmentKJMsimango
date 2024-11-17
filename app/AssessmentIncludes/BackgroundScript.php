<?php

    echo "start \n\r";

    require __DIR__ . '/../../vendor/autoload.php';

    echo "imported \n\r";

    // Parse input from command-line arguments
    if ($argc < 3) {
        exit("Usage: php ExecuteBackgroundJob.php <ClassName> <Method> [<Params>]\n");
    }

    $class_name = $argv[1];
    $method = $argv[2];
    $params = explode(',', $argv[3]);

    $bg_runner = new \App\Jobs\ExecuteBackgroundJob();
    try {
        $bg_runner->run($class_name, $method, $params);
    } catch (Exception $e) {
        echo "Job failed: " . $e->getMessage() . "\n";
    }
