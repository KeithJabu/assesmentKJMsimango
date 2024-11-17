<?php

namespace App\Console\Commands;

use App\AssessmentIncludes\AssessmentInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunBackgroundJob extends Command
{
    /**
     * Running a background job that can take in a class name, method name and with parameters as objects.
     * TO run the function we need a class name and an or a method
     * Parameters are optional, as a method/function can run without one
     *
     * @var string
     */
    protected $signature = 'job:run_background_job {class_name} {method} {params?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This script can run classes or methods in the background, separate from the main Laravel application process and runs in the background';

    /**
     * Instantiate the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //if ($this->argument('class') !== NULL)
            $class_name = $this->argument('class');

        //if ($this->argument('class') !== NULL)
            $method = $this->argument('method');

        $params = $this->argument('params') ? json_decode($this->argument('params'), true) : [];

        // Validate class_name and method log if not fond
        if ( ! $this->isValidJob($class_name, $method)) {
            Log::channel('assessmentLogErrors')->error(
                "Invalid job execution: {$class_name}::{$method}",
                [
                    'request' => ['class_name' => $class_name, '$method' => $method],
                    'message' => 'Invalid job class or method.',
                    'file' => static::class
                ]
            );

            return;
        }

        try {
            runBackgroundJob($class_name, $method, $params);

            Log::channel('assessmentLog')->info("Run Background Job execution successfully.");

        } catch (\Exception $e) {
            Log::channel('assessmentLogErrors')->error(
                "Job execution failed: {$e->getMessage()}",
                [
                    'request' => ['class_name' => $class_name, '$method' => $method, 'params' => $params],
                    'Exception' => $e,
                    'file' => static::class
                ]
            );
            $this->error("Job execution failed.");
        }
    }

    /**
     * List of allowed classes to run in the background for security purposes
     *
     * @param $class_name
     * @param $method
     *
     * @return bool
     */
    private function isValidJob($class_name, $method): bool
    {
        return in_array($class_name, AssessmentInterface::ALLOWED_CLASSES, TRUE) && method_exists($class_name, $method);
    }

    /**
     * Prepare the command for the background execution, and execute the command in the background
     *
     * @param string $class_name
     * @param string $method
     * @param $params
     *
     * @return void
     */
    private function runJobInBackground(string $class_name, string $method, $params): void
    {
        $params_to_json = escapeshellarg(json_encode($params));

        Artisan::call('job:run_background_job', [
            'class_name' => $class_name,
            'method' => $method,
            'params' => $params_to_json
        ]);
    }
}
