<?php
namespace App\AssessmentIncludes;

interface AssessmentInterface
{
    public const ASSESSMENT_ERROR_LOG_PATH = '/logs/background_jobs_errors.log';
    public const ASSESSMENT_LOG_PATH = '/logs/background_jobs.log';

    //TODO: Add more allowed classes in the allowed classes array
    public const ALLOWED_CLASSES = [
        'App\\Jobs\\ExampleRunBackgroundJob1',
        'App\\Jobs\\ExampleRunBackgroundJob2',
    ];
}
