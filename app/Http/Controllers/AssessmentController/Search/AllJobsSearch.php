<?php

namespace App\Http\Controllers\AssessmentController\Search;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use App\Models\BGJobs;
use App\Models\SLB\LekhotlaEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AllJobsSearch
{
    protected $bg_jobs;

    public function __construct(string $status = NULL)
    {
        if (in_array($status, AssessmentInterface::ASSESSMENT_STATUS, TRUE)) {
            $this->bg_jobs = BGJobs::query()->where('status', '=', $status);
        } else {
            $this->bg_jobs = BGJobs::query();
        }
    }

    public function search(Request $request)
    {
        return DataTables::of($this->bg_jobs)
            ->editColumn('name', function (BGJobs $bg_job) {
                return ucfirst($bg_job->class);
            })
            ->editColumn('method', function (BGJobs $bg_job) {
                return ucfirst($bg_job->method);
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('id') && $request->get('id') != NULL) {
                    $query->where('id', '=', $request->get('id'));
                }
                if ($request->has('name') && $request->get('name') != NULL) {
                    $query->where('name', 'LIKE', "%{$request->get('name')}%");
                }
                if ($request->has('description') && $request->get('description') != NULL) {
                    $query->where('description', 'LIKE', "%{$request->get('description')}%");
                }
            })
            ->toJson();
    }
}
