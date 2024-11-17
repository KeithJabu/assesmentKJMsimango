<?php
namespace App\AssessmentIncludes;

interface AssessmentInterface
{
    public const ASSESSMENT_ERROR_LOG_PATH = '/logs/background_jobs_errors.log';
    public const ASSESSMENT_LOG_PATH       = '/logs/background_jobs.log';
    public const COMPLETED                 = 'completed';
    public const FAILED                    = 'failed';
    public const RUNNING                   = 'running';

    //TODO: Add more allowed classes in the allowed classes array
    public const ALLOWED_CLASSES = [
        'App\\Jobs\\ExampleRunBackgroundJob1',
        'App\\Jobs\\ExampleRunBackgroundJob2',
    ];

    /**
     * Log status execution on jobs
     *
     * @param string $message
     * @param string $class_name
     * @param string $method
     * @param string $status
     *
     * @return void
     */
    public function logStatus(string $message, string $class_name, string $method, string $status): void;
}
