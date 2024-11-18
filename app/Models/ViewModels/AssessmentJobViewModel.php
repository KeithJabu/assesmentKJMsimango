<?php

namespace App\Models\ViewModels;

use App\AssessmentIncludes\Classes\AssessmentInterface;
use Spatie\ViewModels\ViewModel;

abstract class AssessmentJobViewModel extends ViewModel implements AssessmentInterface
{
    abstract public function model_data();

    public function getView()
    {
        return $this->view;
    }
}
