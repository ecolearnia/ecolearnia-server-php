<?php
namespace App\EcoLearnia\Modules\Assignment\Evaluation;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\ObjectHelper;

class DefaultEvaluator implements EvaluatorInterface
{
    /**
     * Evaluation handlers
     */
    private $handlers = [];

    public function __construct() {
        $this->registerHandler(new WhenHandler());
    }

    /**
     * Register an evaluator handler
     *
     * @param handler
     */
    public function registerHandler($handler)
    {
        $this->handlers[$handler->name] = $handler;
    }

    /**
     * Evaluate
     * @param Activity $activity - The activity
     * @param SubmissionDetails submissionDetails - The student's submission
     * @return player.EvalResult
     */
    public function evaluate($activity, $submissionDetails)
    {
        $itemVars = ObjectAccessor::get($activity, 'contentInstance.variableDeclarations');
        $combinedSubmissionData = $this->combineSubmissionData($itemVars, $submissionDetails['fields']);

        $attempts = $this->calculateAttempts($activity);
        //console.log('** attemptsLeft=' + attempts.attemptsLeft);
        if ($attempts->attemptsLeft == 0)
        {
            throw new Exception('NoMoreAttempts');
        }

        $responseProcessing = ObjectAccessor::get($activity, 'contentInstance.responseProcessing');
        $fieldEvals = $this->evaluateFields($responseProcessing, $combinedSubmissionData);
        $evalResult = new \stdClass();
        $evalResult->fields = $fieldEvals;
        $evalResult->attemptNum = $attempts->numAttempted + 1;
        $evalResult->attemptsLeft = $attempts->attemptsLeft - 1;
        return $this->calculateAggregate($evalResult, $evalResult->attemptNum);
    }


    /**
     * Calculates the number of attempts lesft before this submission.
     * The number of attempts done so far is obtained by counting the number
     * elements in the array of evaluations.
     *
     * @param Activity $activity - The activity.
     *      Used properties:
     *        item_evalDetailsList
     *        contentInstance.defaultPolicy
     *        policy.maxAttempts
     * @return object
     *      object.numAttempted - The number of submissions so far
     *      object.attemptsLeft - The number of attempts (opportunities) left
     */
    protected function calculateAttempts($activity)
    {
        $numAttempted = (!empty($activity->item_evalDetailsList)) ? count($activity->item_evalDetailsList) : 0;
        $maxAttempts = 1;

        $maxAttempts = ObjectAccessor::get($activity, 'contentInstance.defaultPolicy', $maxAttempts);
        $maxAttempts = ObjectAccessor::get($activity, 'policy.maxAttempts', $maxAttempts);

        $retval = new \stdClass();
        $retval->numAttempted = $numAttempted;
        $retval->attemptsLeft = $maxAttempts - $numAttempted;
        return $retval;
    }

    /**
     * Combine submission data with:
     * 1. variables from the content. This is used for comparison.
     * 2. flattened nested values of itself
     * @param {array} $variableDeclarations - The item's variableDeclarations
     * @param {array} $submissionData - the data that the student has submitted
     */
    protected function combineSubmissionData($variableDeclarations, $submissionData)
    {
        $combinedSubmissionData = [];

        // add values from flattened nested objects,
        // eg. field1: {key, value} into field1_key and field1_value
        $combinedSubmissionData = array_merge($submissionData, ObjectHelper::dehydrate($submissionData, '', '_'));

        // Add variables with 'var_' prefix
        $vars = [];
        foreach($variableDeclarations as $varName => $varDecl )
        {
            $vars['var_' . $varName] = ObjectAccessor::get($varDecl, 'value');
        }
        $combinedSubmissionData = array_merge($combinedSubmissionData, $vars);
        return $combinedSubmissionData;
    }

    /**
     * Calculate the aggregate score
     * @param {player.evalDetails} $evalResult
     *          Uses
     *              $evalResult->fields
     *          Adds
     *              $evalResult->aggregate->score
     *              $evalResult->aggregate->pass
     * @param {number} $attemptNum  - the number of attempt , score diminishing factor
     */
    protected function calculateAggregate(&$evalResult, $attemptNum)
    {
       // Attempt
       $_attemptNum = $attemptNum || 1;
       // @todo get the passThreshold from config
       $passThreshold = 0.9;
       // Calculate the aggregate score
       $sum = 0.0;
       $aggregatePass = true;
       foreach ($evalResult->fields as $fieldName => $fieldResult) {

           if (array_key_exists('score', $fieldResult)) {
               // Calculate pass/no-pass per field
               $pass = ($fieldResult['score'] >= $passThreshold);
               $evalResult->fields[$fieldName]['pass'] = $pass;
               //console.log('** fieldResult.pass=' + JSON.stringify(fieldResult.pass));
               if (!$pass) {
                   $aggregatePass = false;
               }
               $sum += $fieldResult['score'];
           }
       }

       // Round to two decimals
       $aggregateScore = round($sum / count($evalResult->fields) / $_attemptNum, 2 );

       if (!(array_key_exists('aggregate', $evalResult))) {
           if (!property_exists($evalResult, 'aggregate')) {
               $evalResult->aggregate = new \stdClass();
           }
       }
       $evalResult->aggregate->score = $aggregateScore;
       $evalResult->aggregate->pass = $aggregatePass;

       //console.log('** evalResult:' + JSON.stringify(evalResult, null, 2));
       return $evalResult;
   }

   /**
    * Evaluate the student submission against the provided rule
    * For php implementation, use
    * http://symfony.com/doc/current/components/expression_language/syntax.html
    *
    * @param {Object} rule
    * @param {Array.<{fieldId, answered}>} answer - student submission
    *
    * @return array
    *      array of fields with their respective evaluation result
    */
   protected function evaluateFields($rule, $submissionData)
   {
       //return Promise.reject("Testing static reject");

       // @type {Map.<{string} fieldName, {player.FieldEvalResult}>}
       $outcomes = [];

       foreach ($rule as $statementKey => $statement) {
           // Currently only 'whenHandler' is supported

           if (!array_key_exists($statementKey, $this->handlers)) {
               // @todo Log
               continue;
           }
           $statementHandler = $this->handlers[$statementKey];

           try {
               // using primivite for construct so it can break
               if ($statementHandler && $statement) {
                   // Accumulate outcomes.
                   $outcomes = array_merge(
                       $statementHandler->evaluate($statement, $submissionData));
               }
           } catch (Exception $error) {
               return $error;
           }
       }

       //console.log('outcomes=' + JSON.stringify(outcomes));
       return $outcomes;
   }

   // exposing private methods for testing purpose
   public function calculateAttempts_($activity)
   {
       return $this->calculateAttempts($activity);
   }
   public function combineSubmissionData_($variableDeclarations, $submissionData)
   {
       return $this->combineSubmissionData($variableDeclarations, $submissionData);
   }
   public function calculateAggregate_(&$evalResult, $attemptNum)
   {
       return $this->calculateAggregate($evalResult, $attemptNum);
   }

   public function evaluateFields_($rule, $submissionData)
   {
       return $this->evaluateFields($rule, $submissionData);
   }
   //
}
