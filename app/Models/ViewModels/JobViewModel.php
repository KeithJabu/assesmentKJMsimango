<?php

namespace App\Models\ViewModels;

use App\Models\BGJobs;
use Illuminate\Support\Collection;

class JobViewModel extends AssessmentJobViewModel
{
    protected ?BGJobs $bg_jobs;

    public function __construct(BGJobs $bg_jobs = NULL)
    {
        $this->bg_jobs = $bg_jobs;
    }

    public function model_data()
    {
        return $this->bg_jobs ?? new BGJobs();
    }

    public function tab_list(): Collection
    {
        $tab_list = collect([
            [
                'title' => 'All Jobs',
                'template' => 'all-list'
            ],
            [
                'title' => self::COMPLETED .' Jobs',
                'template' => 'completed-list'
            ],
            [
                'title' => self::RUNNING .'/in-progress Jobs',
                'template' => 'running-list'
            ],
            [
                'title' => self::FAILED .' Jobs',
                'template' => 'failed-list'
            ]
        ]);

        return $tab_list;
    }

}
