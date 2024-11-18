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
    protected $signature = 'job:run_background_job {class_name} {method} {params?} {delay_in_seconds=5} {priority=0}';

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

        $this->BG_jobs = BGJobs::create([
            'class' => $this->getClassName($class_name),
            'method' => $method,
            'parameters' => json_encode($params),
            'status' => AssessmentInterface::RUNNING,
        ]);

        $this->logStatus(
            $this->getClassName($class_name) . "::$method: Job executing start",
            $class_name, $method, static::RUNNING, static::class
        );

        try {
            dump('$delay_in_seconds > 0', $delay_in_seconds > 0);
            if ($delay_in_seconds > 0) {
                sleep($delay_in_seconds);
            }

            $class = app($this->getClassName($class_name));
            $class->$method(...$params);

            $this->logStatus("Job executed successfully executed",
                $class_name, $method, AssessmentInterface::COMPLETED, static::class
            );

            //for ($attempt = 0; $attempt < $retry_attempts; $attempt++) {
            //    dump('$attempt', $attempt);
            //    try {
            //        $jobs->status = AssessmentInterface::RUNNING;
            //        $jobs->retry_count = $attempt;
            //        $jobs->save();
            //
            //        $bg_job_runner = new ExecuteBackgroundJob();
            //        $bg_job_runner->run($class_name, $method, $params);
            //
            //        $this->logStatus("Job executed successfully executed on attempt: $attempt.",
            //            $class_name, $method, AssessmentInterface::COMPLETED, static::class);
            //
            //        $jobs->status = AssessmentInterface::COMPLETED;
            //        $jobs->retry_count = $attempt;
            //        $jobs->save();
            //
            //        break;
            //    } catch (Exception $e) {
            //        $this->logStatus("Retry $attempt for job $class_name@$method failed: " . $e->getMessage(),
            //            $class_name, $method, AssessmentInterface::COMPLETED, static::class);
            //
            //        $jobs->status = AssessmentInterface::FAILED;
            //        $jobs->retry_count = $attempt;
            //        $jobs->save();
            //
            //        if ($attempt + 1 === $retry_attempts) {
            //            $jobs->status = AssessmentInterface::FAILED;
            //            $jobs->retry_count = $attempt;
            //            $jobs->save();
            //
            //            throw $e;
            //        }
            //    }
            //}
        } catch (Exception $e) {
            $this->logStatus("Job failed: $class_name::$method" . $e->getMessage(),
                $class_name, $method, AssessmentInterface::COMPLETED, static::class
            );

            $this->retryJob($class_name, $method, $params);
        }
    }

    /**
     * List of allowed classes to run in the background for security purposes
     *  Validate class_name and method log if fond or not
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
     * Implement retry logic, with max retries set to default 3 times, with max delay set to default 5 seconds
     *
     * @param $class_name
     * @param $method
     * @param $parameters
     *
     * @return void
     */
    private function retryJob($class_name, $method, $parameters): void
    {
        $attempts = 0;
        $max_retries = 3;
        $retry_delay = 5;

        while ($attempts < $max_retries) {
            sleep($retry_delay);

            try {
                // Validate class_name and method log if fond
                if ($this->isValidJob($class_name, $method)) {
                    $class = app($this->getClassName($class_name));
                    $class->$method(...$parameters);
                    $this->logStatus(
                        "Executed job with retry: $attempts for $class_name::$method",
                        $class_name, $class_name, static::COMPLETED, static::class
                    );

                    return;
                }
            } catch (\Exception $e) {
                $attempts++;
                $this->logStatus(
                    "Retry $attempts failed: " . $e->getMessage(),
                    $class_name, $class_name, static::FAILED, static::class
                );
            }
        }

        if ($attempts >= $max_retries) {
            $this->logStatus(
                "Job failed after $max_retries retries: " .$this->getClassName($class_name) . "::$method",
                $class_name, $class_name, static::FAILED, static::class
            );
        }
    }
}
