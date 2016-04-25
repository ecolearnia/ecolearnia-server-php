<?php
namespace App\EcoLearnia\Modules\Assignment\Evaluation;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\ObjectHelper;

interface EvaluatorInterface
{
    /**
     * Evaluate
     */
    public function evaluate($activity, $submissionDetails);
}
