<?php

namespace App\Console\Commands;

use App\AssessmentIncludes\AssessmentInterface;
use App\AssessmentIncludes\LogTrait;
use App\Jobs\ExecuteBackgroundJob;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunBackgroundJob extends Command implements AssessmentInterface
{
    use LogTrait;

    /**
     * Running a background job that can take in a class name, method name and with parameters as objects.
     * TO run the function we need a class name and an or a method
     * Parameters are optional, as a method/function can run without one
     *
     * @var string
     */
    protected $signature = 'job:run_background_job {class_name} {method} {params?} {retryAttempts=3} {delayInSeconds=0} {priority=0}';

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
        $class_name = app($this->getClassName($this->argument('class_name')));
        $method     = $this->argument('method');
        $params     = $this->argument('params') ? json_decode($this->argument('params'), true) : [];
        $retry_attempts = (int)$this->argument('retry_attempts');
        $delay_in_seconds = (int)$this->argument('delay_in_seconds');

        // Validate class_name and method log if not fond
        if ( ! $this->isValidJob($class_name, $method)) {
            $this->logStatus(
                "Invalid job class or method. Invalid job execution: {$class_name}::{$method}, ",
                $class_name, $class_name, static::FAILED
            );

            return;
        }

        try {
            if ($delay_in_seconds > 0) {
                sleep($delay_in_seconds);
            }

            for ($attempt = 0; $attempt < $retry_attempts; $attempt++) {
                try {
                    ExecuteBackgroundJob::execute($class_name, $method, $params);
                    $this->logStatus("Job executed successfully on attempt $attempt.",
                        $class_name, $method, AssessmentInterface::COMPLETED, static::class);

                    break;
                } catch (Exception $e) {
                    $this->logStatus("Retry $attempt for job $class_name@$method failed: " . $e->getMessage(),
                        $class_name, $method, AssessmentInterface::COMPLETED);
                    if ($attempt + 1 === $retry_attempts) {
                        throw $e;
                    }
                }
            }
        } catch (Exception $e) {
            $this->logStatus("Job failed: " . $e->getMessage(),
                $class_name, $method, AssessmentInterface::COMPLETED);
        }






        try {
            runBackgroundJob($class_name, $method, $params);
            $this->logStatus('Run Background Job execution successfully.', $class_name, $class_name, static::COMPLETED);
        } catch (Exception $e) {
            $this->logStatus("Job execution failed: {$e->getMessage()}", $class_name, $class_name, static::FAILED);
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
     * TODO:: might not be used
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
            'method'     => $method,
            'params'     => $params_to_json
        ]);
    }

    /**
     * @param string $message
     * @param string $class_name
     * @param string $method
     * @param string $status
     *
     * @return void
     */
    public function logStatus(string $message, string $class_name, string $method, string $status): void
    {
        $logMessage = [
            'timestamp' => Carbon::now()->format('Y-m-d HH:i:s'),
            'class'     => $class_name,
            'method'    => $method,
            'status'    => $status,
            'message'   => $message,
            'file'      => static::class,
        ];

        switch ($status) {
            case static::FAILED:
                Log::channel('assessmentLogErrors')->error($message, $logMessage);
                break;
            default:
                Log::channel('assessmentLog')->error($message, $logMessage);
                break;
        }
    }
}
