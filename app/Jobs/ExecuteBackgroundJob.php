<?php

namespace App\Jobs;

use App\AssessmentIncludes\AssessmentInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ExecuteBackgroundJob implements AssessmentInterface
{
    /**
     * Create a new job instance.
     */
    public function __construct() {}


    /**
     * @param string $class_name
     * @param string $method
     * @param array $params
     *
     * @return mixed
     * @throws Exception
     */
    public function run(string $class_name, string $method, array $params = [])
    {
        try {
            if ( ! in_array($class_name, AssessmentInterface::ALLOWED_CLASSES, TRUE)) {
                $this->logStatus("Class name is Not allowed: $class_name", $class_name, $method, static::FAILED);

                throw new Exception("Class name is Not allowed: $class_name");
            }

            if ( ! method_exists($class_name, $method)) {
                $this->logStatus("No Method $method exist in $class_name", $class_name, $method, static::FAILED);

                throw new Exception("No Method $method exist in $class_name");
            }

            $this->logStatus("$class_name::$method: Job executing", $class_name, $method, static::RUNNING);

            $instance = new $class_name();
            $response = call_user_func_array([$instance, $method], $params);

            $this->logStatus("$class_name::$method: Job executed successfully", $class_name, $method, static::COMPLETED);

            return $response;
        } catch (Exception $exception) {
            $this->logStatus("Job execution failure", $class_name, $method, static::FAILED);
            throw $exception;
        }

    }

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

        if ($status == static::FAILED) {
            Log::channel('assessmentLogErrors')->error($message, $logMessage);
        } else {
            Log::channel('assessmentLog')->error($message, $logMessage);
        }
    }
}
