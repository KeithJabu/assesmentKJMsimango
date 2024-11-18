<?php

namespace App\Jobs;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use App\AssessmentIncludes\Classes\LogTrait;
use Exception;
use Illuminate\Support\Facades\Facade;

class ExecuteBackgroundJob extends Facade implements AssessmentInterface
{
    use LogTrait;

    private int $max_retries = 3;
    private int $retry_delay = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(int $max_retries = 3, int $retry_delay = 5)
    {
        $this->max_retries = $max_retries;
        $this->retry_delay = $retry_delay;
    }

    /**
     * @param string $class_name
     * @param string $method
     * @param array $params
     *
     * @return string
     * @throws Exception
     */
    public function run(string $class_name, string $method, array $params = [])
    {
        if ( ! array_key_exists($class_name, self::ALLOWED_CLASSES)) {
            $this->logStatus("Class name is Not allowed: $class_name",
                $class_name, $method, static::FAILED, static::class
            );

            echo "\n You can only use these sets of class names:";
            foreach (self::ALLOWED_CLASSES as $objects => $classes) {
                echo "\n Use class name: '$objects' to trigger the '$classes' class";
            }

            throw new Exception("Class name is Not allowed: $class_name");
        }

        $class = app(self::getClassName($class_name));
        if ( ! method_exists($class, $method)) {
            $this->logStatus(
                "No Method $method exist in $class_name",
                $class_name, $method, static::FAILED, static::class
            );

            throw new Exception("No Method $method exist in $class_name");
        }

        try {
            $this->runJobInBackground($class_name, $method, $params);

            return '\n Completed script';
        } catch (Exception $exception) {
            $this->logStatus("Job execution failure " . $exception->getMessage(),
                $class_name, $method, static::FAILED, static::class
            );

            throw $exception;
        }
    }

    /**
     * TODO:: might not be used
     * Prepare the command for the background execution, and execute the command in the background
     *
     * @param string $class_name
     * @param string $method
     * @param array $params
     *
     * @return void
     */
    private function runJobInBackground(string $class_name, string $method, array $params): void
    {
        $params_to_string = $params;

        $command = sprintf(
            'php %s artisan job:run_background_job %s %s "%s" > /dev/null 2>&1 &',
            '',
            $class_name,
            $method,
            json_encode($params_to_string)
        );

        // Execute the command in the background (platform-dependent)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = sprintf('start /B php %s artisan job:run_background_job %s %s "%s"',
                '',
                $class_name,
                $method,
                json_encode($params_to_string)
            );
        }

        // Execute the command to run in the background
        exec($command);
        dump('$command used for artisan: ', $command);

        $this->logStatus("$class_name::$method: Job executed successfully",
            $class_name, $method, static::COMPLETED, static::class
        );

    }
}
