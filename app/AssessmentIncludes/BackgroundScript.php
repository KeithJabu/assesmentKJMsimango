<?php

namespace App\AssessmentIncludes;

    echo "start \n\r";

    require __DIR__ . '/../../vendor/autoload.php';

    // Show error Parse inputs needed from command-line arguments
    if ($argc < 3) {
        exit("Usage: php ExecuteBackgroundJob.php <ClassName> <Method> [<Params>]\n");
    }

    $class_name = $argv[1];
    $method = $argv[2];
    $params = explode(',', $argv[3]);

    $bg_job_runner = new \App\Jobs\ExecuteBackgroundJob();
    try {
        $response = $bg_job_runner->run($class_name, $method, $params);

        echo $response;
        echo "\n Processes!";
    } catch (\Exception $e) {
        echo "\n Job failed: " . $e->getMessage() . "\n";
    }
