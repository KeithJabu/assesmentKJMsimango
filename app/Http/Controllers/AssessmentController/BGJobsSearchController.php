<?php

namespace App\Http\Controllers\AssessmentController;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use App\Http\Controllers\AssessmentController\Search\AllJobsSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BGJobsSearchController extends Controller
{
    public function searchDataTable(Request $request, $model_type): JsonResponse
    {
        $Search = NULL;

        switch ($model_type) {
            case 'all':
                $Search = new AllJobsSearch();
                break;
            case AssessmentInterface::RUNNING:
                $Search = new AllJobsSearch(AssessmentInterface::RUNNING);
                break;
            case AssessmentInterface::COMPLETED:
                $Search = new AllJobsSearch(AssessmentInterface::COMPLETED);
                break;
            case AssessmentInterface::FAILED:
                $Search = new AllJobsSearch(AssessmentInterface::FAILED);
                break;
        }

        return $Search->search($request);
    }
}
