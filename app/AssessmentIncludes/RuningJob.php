<?php

foreach (\App\AssessmentIncludes\Classes\AssessmentInterface::ALLOWED_CLASSES as $key => $CLASS) {
    runBackgroundJob($CLASS, 'startCounter', [50]);
}
