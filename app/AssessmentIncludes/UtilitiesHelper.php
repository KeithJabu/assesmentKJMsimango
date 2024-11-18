<?php

use App\Jobs\ExecuteBackgroundJob;

if ( ! function_exists('runBackgroundJob')) {
    /**
     * Prepare the command for the background execution, and execute the command in the background
     *
     * @param string $class
     * @param string $method
     * @param array $params
     *
     * @return void
     * @throws Exception
     */
    function runBackgroundJob(string $class, string $method, array $params = []): void
    {
        $run_bg_job_runner = new ExecuteBackgroundJob();
        $run_bg_job_runner->run($class, $method, $params);
    }
}
