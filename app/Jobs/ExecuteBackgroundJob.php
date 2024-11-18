<?php

namespace App\Jobs;

use App\AssessmentIncludes\AssessmentInterface;
use App\AssessmentIncludes\LogTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ExecuteBackgroundJob extends Facade implements AssessmentInterface
{
    use LogTrait;

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
            if ( ! array_key_exists($class_name, self::ALLOWED_CLASSES)) {
                $this->logStatus("Class name is Not allowed: $class_name", $class_name, $method, static::FAILED);

                throw new Exception("Class name is Not allowed: $class_name");
            }

            $class = app(self::getClassName($class_name));
            if ( ! method_exists($class, $method)) {
                $this->logStatus("No Method $method exist in $class_name", $class_name, $method, static::FAILED);

                throw new Exception("No Method $method exist in $class_name");
            }

            $this->logStatus("$class_name::$method: Job executing s", $class_name, $method, static::RUNNING);

            $instance = new $class;
            $response = call_user_func_array([$instance, $method], $params);

            $this->logStatus("$class_name::$method: Job executed successfully", $class_name, $method, static::COMPLETED);

            return $response;
        } catch (Exception $exception) {
            $this->logStatus("Job execution failure " . $exception->getMessage(), $class_name, $method, static::FAILED);
            throw $exception;
        }
    }
}
