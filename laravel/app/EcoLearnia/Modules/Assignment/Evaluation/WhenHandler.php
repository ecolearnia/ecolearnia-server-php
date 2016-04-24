<?php
namespace App\EcoLearnia\Modules\Assignment\Evaluation;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\ObjectHelper;

class WhenHandler
{
    private $exprEngine;

    public $name = 'when';

    public function __construct() {
        $this->exprEngine = new ExpressionLanguage();
    }

    /**
     * Evaluate a 'when' rule statement
     *
     * @param {array} $statement  - The statement object
     * @param {array} $submissionData  - The key value pair of submitted data
     */
    public function evaluate($statement, $submissionData)
    {
        $outcomes = [];

        foreach ($statement as $caseClause) {
            $caseExpr = null;
            $case = ObjectAccessor::get($caseClause, 'case');

            if (is_string($case)) {
                $caseExpr = str_replace('$', '', $case);

                try {
                    $result = $this->exprEngine->evaluate($caseExpr, $submissionData);
                    if ($result === true) {
                        //console.log('[' + caseExpr + '] condition met');
                        $then = ObjectAccessor::get($caseClause, 'then');
                        foreach($then as $thenKey => $thenVal) {
                            $outcomes[$thenKey] = $thenVal;
                        }
                    } else {
                        //console.log('[' + caseExpr + '] condition not met');
                    }
                } catch (Exception $exception) {

                    if ($exception->getMessage() == "Undefined symbol")
                    {
                        // ignore, i.e. skip as this the symbol was not defined
                        // at the moment of submission
                    } else {
                        // re-throw
                        throw $exception;
                    }
                }
            } // if (_.isString(case))
        }

        return ObjectHelper::hydrate($outcomes);
    }
}
