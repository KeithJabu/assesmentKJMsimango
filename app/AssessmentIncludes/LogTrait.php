<?php
namespace App\AssessmentIncludes;

use App\Jobs\CountHundredJob;
use Carbon\Carbon;

trait LogTrait
{
    public function logStatus(string $message, string $class_name, string $method, string $status): void
    {
        $log_message = [
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'class' => $class_name,
            'method' => $method,
            'status' => $status,
            'message' => $message,
            'file' => static::class,
        ];

        if ($status === AssessmentInterface::FAILED) {
            $this->addLogToFile(AssessmentInterface::ASSESSMENT_ERROR_LOG_FILE, $log_message);
        } else {
            echo $status . ": status \n";
            $this->addLogToFile(AssessmentInterface::ASSESSMENT_LOG_FILE, $log_message);
            echo $status . ": check file \n";
        }
    }

    /**
     * @param string $file
     * @param array $content
     *
     * @return void
     */
    public function addLogToFile(string $file, array $content): void
    {
        if ( ! file_exists(AssessmentInterface::ASSESSMENT_LOG_PATH . $file)) {
            echo ": create \n";
            // Create the file if it doesn't exist
            $handle = fopen(AssessmentInterface::ASSESSMENT_LOG_PATH . $file, 'w');
        } else {
            echo ": open \n";
            $handle = fopen(AssessmentInterface::ASSESSMENT_LOG_PATH . $file, 'a+');
        }

        echo 'write again';
        if ($handle) {
            fputs($handle, json_encode($content) . PHP_EOL ."\r\n");
            fclose($handle);
        }
    }

    /**
     * @param string $class_name
     *
     * @return string
     */
    public function getClassName(string $class_name): string
    {
        return AssessmentInterface::ALLOWED_CLASSES[$class_name];
    }
}
