<?php

if ( ! function_exists('runBackgroundJob')) {
    /**
     * Prepare the command for the background execution, and execute the command in the background
     *
     * @param string $class
     * @param string $method
     * @param array $params
     *
     * @return void
     */
    function runBackgroundJob(string $class, string $method, array $params = []): void
    {
        // Prepare the command for the background execution
        $params_to_json = escapeshellarg(json_encode($params));

        $command = PHP_BINARY . " -f " . base_path('artisan') . " job:execute {$class} {$method} {$params_to_json} > /dev/null 2>&1 &";

        // Execute the command in the background (platform-dependent)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows-specific background execution
            pclose(popen($command, 'r'));
        } else {
            // Unix-based (Linux/Mac) background execution
            exec($command);
        }
    }
}
