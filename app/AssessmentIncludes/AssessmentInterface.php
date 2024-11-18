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
}
