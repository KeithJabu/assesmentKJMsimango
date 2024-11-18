<?php

namespace App\Console\Commands;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use App\AssessmentIncludes\Classes\LogTrait;
use App\Jobs\ExecuteBackgroundJob;
use App\Models\BGJobs;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
    protected $signature = 'job:run_background_job {class_name} {method} {params?} {retry_attempts=3} {delay_in_seconds=2} {priority=0}';

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
        $class_name = $this->argument('class_name');
        $method     = $this->argument('method');
        $params     = $this->argument('params') ? json_decode($this->argument('params'), true) : [];
        $retry_attempts = (int)$this->argument('retry_attempts');
        $delay_in_seconds = (int)$this->argument('delay_in_seconds');

        // Validate class_name and method log if not fond
        if ( ! $this->isValidJob($class_name, $method)) {
            $this->logStatus(
                "Invalid job class or method. Invalid job execution: {$class_name}::{$method}, ",
                $class_name, $class_name, static::FAILED, static::class
            );

            return;
        }

        /** @var BGJobs $jobs */

        $jobs = BGJobs::create([
            'class' => $this->getClassName($class_name),
            'method' => $method,
            'parameters' => json_encode($params),
            'status' => AssessmentInterface::RUNNING,
        ]);

        try {
            if ($delay_in_seconds > 0) {
                sleep($delay_in_seconds);
            }

            for ($attempt = 0; $attempt < $retry_attempts; $attempt++) {
                try {
                    $jobs->status = AssessmentInterface::RUNNING;
                    $jobs->retry_count = $attempt;
                    $jobs->save();

                    $bg_job_runner = new ExecuteBackgroundJob();
                    $bg_job_runner->run($class_name, $method, $params);

                    $this->logStatus("Job executed successfully executed on attempt: $attempt.",
                        $class_name, $method, AssessmentInterface::COMPLETED, static::class);

                    $jobs->status = AssessmentInterface::COMPLETED;
                    $jobs->retry_count = $attempt;
                    $jobs->save();

                    break;
                } catch (Exception $e) {
                    $this->logStatus("Retry $attempt for job $class_name@$method failed: " . $e->getMessage(),
                        $class_name, $method, AssessmentInterface::COMPLETED, static::class);

                    $jobs->status = AssessmentInterface::FAILED;
                    $jobs->retry_count = $attempt;
                    $jobs->save();

                    if ($attempt + 1 === $retry_attempts) {
                        $jobs->status = AssessmentInterface::FAILED;
                        $jobs->retry_count = $attempt;
                        $jobs->save();

                        throw $e;
                    }
                }
            }
        } catch (Exception $e) {
            $this->logStatus("Job failed: " . $e->getMessage(),
                $class_name, $method, AssessmentInterface::COMPLETED, static::class);
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
        return array_key_exists($class_name, self::ALLOWED_CLASSES)
            && method_exists(app($this->getClassName($class_name)), $method);
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

}
