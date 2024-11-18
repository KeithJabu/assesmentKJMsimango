<?php
namespace App\AssessmentIncludes\Classes;

use App\Models\BGJobs;
use Carbon\Carbon;

trait LogTrait
{
    protected ?BGJobs $BG_jobs;

    /**
     * Process logging status
     *
     * @param string $message
     * @param string $class_name
     * @param string $method
     * @param string $status
     * @param string $from_file
     *
     * @return void
     */
    public function logStatus(string $message, string $class_name, string $method, string $status, string $from_file): void
    {
        $log_message = [
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'class'     => $class_name,
            'method'    => $method,
            'status'    => $status,
            'message'   => $message,
            'file'      => $from_file,
        ];

        if ($status === AssessmentInterface::FAILED) {
            //Log::channel('assessmentLogErrors')->error($message, $log_message);
            $this->addLogToFile(AssessmentInterface::ASSESSMENT_ERROR_LOG_FILE, $log_message);
        } else {
            //Log::channel('assessmentLog')->info($message, $log_message);
            $this->addLogToFile(AssessmentInterface::ASSESSMENT_LOG_FILE, $log_message);
        }
    }

    /**
     * Create or add on to logging file
     *
     * @param string $file
     * @param array $content
     *
     * @return void
     */
    public function addLogToFile(string $file, array $content): void
    {
        if ( ! file_exists(AssessmentInterface::ASSESSMENT_LOG_PATH . $file)) {
            echo ": create Logging File\n";
            // Create the file if it doesn't exist
            $handle = fopen(AssessmentInterface::ASSESSMENT_LOG_PATH . $file, 'w');
        } else {
            $handle = fopen(AssessmentInterface::ASSESSMENT_LOG_PATH . $file, 'a+');
        }

        if ($handle) {
            fputs($handle, json_encode($content) . PHP_EOL . "\r\n");
            fclose($handle);
        }
    }

    /**
     * Get application allowed full class name
     *
     * @param string $class_name
     *
     * @return string
     */
    public function getClassName(string $class_name): string
    {
        return AssessmentInterface::ALLOWED_CLASSES[$class_name];
    }
}
