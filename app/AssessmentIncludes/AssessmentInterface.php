<?php
namespace App\AssessmentIncludes;

use App\Jobs\CountHundredJob;

interface AssessmentInterface
{
    public const ASSESSMENT_LOG_PATH       = './storage/logs/';
    public const ASSESSMENT_ERROR_LOG_FILE = 'background_jobs_errors.log';
    public const ASSESSMENT_SCRIPT         = 'app/AssessmentIncludes/BackgroundScript.php';
    public const ASSESSMENT_LOG_FILE       = 'background_jobs.log';
    public const COMPLETED                 = 'completed';
    public const FAILED                    = 'failed';
    public const RUNNING                   = 'running';

    //TODO: Add more allowed classes in the allowed classes array
    public const ALLOWED_CLASSES = [
        'counter' => Counter::class,
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
}
