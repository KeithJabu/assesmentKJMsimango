<?php

namespace App\AssessmentIncludes;

use App\Jobs\CountHundredJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Counter
{
    use LogTrait;

    protected int $count;
    protected Collection $number_count;

    public function __construct() {
        $this->count = 0;
        $this->number_count = collect();
    }

    /**
     * Dispatch counter to count uop to number of times
     *
     * @param int $number_of_time
     *
     * @return void
     */
    public function startCounter(int $number_of_time = 100): void
    {
        for ($this->count = 1; $this->count <= $number_of_time; $this->count++) {
            if ($number_of_time > $this->count) {
                $this->number_count->put('number_count', ['count_at', $this->count]);

                sleep(2);

                $this->count++;
            } else {
                break;
            }
        }

        $this->addLogToFile(AssessmentInterface::ASSESSMENT_LOG_FILE, ['CountHundredJob Completed' => ['data' => $this->number_count->toArray()]]);
        //Log::channel('assessmentLog')->error('CountHundredJob Completed', $this->number_count->toArray());

        //dispatch(new CountHundredJob($number_of_time));
    }
}
