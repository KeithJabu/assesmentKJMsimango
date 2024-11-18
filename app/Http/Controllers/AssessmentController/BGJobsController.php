<?php

namespace App\Http\Controllers\AssessmentController;

use App\Http\Controllers\Controller;
use App\Models\ViewModels\JobViewModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class BGJobsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function index(): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('layouts.view-jobs', new JobViewModel());
    }


}
