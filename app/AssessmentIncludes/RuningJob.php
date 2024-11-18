<?php

namespace App\AssessmentIncludes;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use Exception, App\AssessmentIncludes\Classes\LogTrait;

class RuningJob implements AssessmentInterface
{
    use LogTrait;

    /**
     * @throws Exception
     */
    public function runallJobs(bool $confirm = FALSE) {
        if ($confirm) {
            foreach (static::ALLOWED_CLASSES as $key => $CLASS) {
                try {
                    runBackgroundJob($CLASS, 'startCounter', [100]);
                } catch (Exception $e) {
                    $this->logStatus('Failed Job ' . $e->getMessage(), $CLASS, 'startCounter', AssessmentInterface::FAILED, static::class);
                    throw new Exception($e->getMessage());
                }

                try {
                    runBackgroundJob($CLASS, 'startCounter', [350]);
                } catch (Exception $e) {
                    $this->logStatus('Failed Job ' . $e->getMessage(), $CLASS, 'startCounter', AssessmentInterface::FAILED, static::class);
                    throw new Exception($e->getMessage());
                }

                try {
                    runBackgroundJob($CLASS, 'startCounter', [450]);
                } catch (Exception $e) {
                    $this->logStatus('Failed Job ' . $e->getMessage(), $CLASS, 'startCounter', AssessmentInterface::FAILED, static::class);
                    throw new Exception($e->getMessage());
                }

                try {
                    runBackgroundJob($CLASS, 'startCounter', [452]);
                } catch (Exception $e) {
                    $this->logStatus('Failed Job ' . $e->getMessage(), $CLASS, 'startCounter', AssessmentInterface::FAILED, static::class);
                    throw new Exception($e->getMessage());
                }

                try {
                    runBackgroundJob($CLASS, 'startCounter', [1]);
                } catch (Exception $e) {
                    $this->logStatus('Failed Job ' . $e->getMessage(), $CLASS, 'startCounter', AssessmentInterface::FAILED, static::class);
                    throw new Exception($e->getMessage());
                }
            }
        }
    }
}
