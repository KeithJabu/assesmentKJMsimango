<?php

namespace App\Jobs;

use App\AssessmentIncludes\AssessmentInterface;
use App\AssessmentIncludes\LogTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CountHundredJob implements AssessmentInterface
{
    use LogTrait;

    protected int $count;
    protected int $limit;
    protected Collection $number_count;

    /**
     * Create a new job instance.
     */
    public function __construct(int $number_count = 100) {
        $this->count = 0;
        $this->limit = $number_count;
        $this->number_count = collect();
    }

    /**
     * Execute the job. TODO NOT USED
     */
    public function handle(): void
    {
        for ($this->count = 1; $this->count <= $this->limit; $this->count++) {
            if ($this->limit > $this->count) {
                $this->number_count->put('number_count', $this->count);

                sleep(2);

                $this->count++;
            } else {
                break;
            }
        }

        Log::channel('assessmentLog')->error('CountHundredJob Completed', $this->number_count->toArray());

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
