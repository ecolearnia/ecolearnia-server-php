<?php
namespace App\EcoLearnia\Modules\Assignment\Evaluation;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\ObjectHelper;

class DefaultEvaluator
{
    /**
     * Evaluation handlers
     */
    private handlers = [];

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
     */
    public function evaluate($activity, $submissionDetails)
    {
        $itemVars = ObjectAccessor::get($activity, 'contentInstance.variableDeclarations');
        $combinedSubmissionData = $this->combineSubmissionData_(itemVars, $submissionDetails['fields']);

        $attempts = $this->calculateAttempts(nodeDetails);
        //console.log('** attemptsLeft=' + attempts.attemptsLeft);
        if (attempts.attemptsLeft == 0)
        {
            throw new Error('NoMoreAttempts');
        }

        $fieldEvals = $this->evaluateFields_($activity->content->responseProcessing, $combinedSubmissionData)
        $evalResult = new stdClass()
        $evalResult->fields = $fieldEvals;
        $evalResult->attemptNum = $attempts->numAttempted + 1
        $evalResult->attemptsLeft = $attempts->attemptsLeft - 1;
        return $this->calculateAgregate_($evalResult, $evalResult.attemptNum);
    }


    /**
     * Calculates the number of attempts lesft before this submission.
     */
    protected function calculateAttempts($activity)
    {
        $numAttempted = !empty($activity->item_evalDetailsList) ? count($activity->item_evalDetailsList) : 0;
        $maxAttempts = 1;

        if (!empty($activity->contentInstance->defaultPolicy)) {
            $maxAttempts = $activity->content->defaultPolicy->maxAttempts || $maxAttempts;
        }

        if (!empty($activity->policy)) {
            $maxAttempts = $activity->policy->maxAttempts || $maxAttempts;
        }

        $retval = stdClass();
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
    protected function combineSubmissionData_($variableDeclarations, $submissionData)
    {
        $combinedSubmissionData = [];

        // add values from flattened nested objects,
        // eg. field1: {key, value} into field1_key and field1_value
        $combinedSubmissionData = array_merge($submissionData, ObjectHelper::dehydrate($submissionData, '', '_'));

        // Add variables with 'var_' prefix
        $vars = [];
        foreach($variableDeclarations as $varName)
        {
            $vars['var_' + $varName] = $variableDeclarations->{$varName}->value;
        }
        $combinedSubmissionData = array_merge($combinedSubmissionData, $vars);
        return $combinedSubmissionData;
    }

    /**
     * Calculate the aggregate score
     * @param {player.item_evalDetailsList} outcomes
     * @param {number} attemptNum  - the number of attempt , score diminishing factor
     */
    protected function calculateAgregate_(&$evalResult, $attemptNum)
    {
       // Attempt
       $_attemptNum = $attemptNum || 1;
       // @todo get the passThreshold from config
       $passThreshold = 0.9;
       // Calculate the aggregate score
       $sum = 0;
       $aggregatePass = true;
       foreach ($evalResult->fields as $fieldName => $fieldResult) {

           if (array_key_exists('score', $fieldResult) {
               // Calculate pass/no-pass per field
               $fieldResult['pass'] = ($fieldResult['score'] > $passThreshold);
               //console.log('** fieldResult.pass=' + JSON.stringify(fieldResult.pass));
               if (!$fieldResult['pass']){
                   $aggregatePass = false;
               }
               $sum += $fieldResult['score'];
           }
       }

       // Round to two decimals
       $aggregateScore = round($sum / count($evalResult->fields) / $_attemptNum );

       if (!(array_key_exists('aggregate', $evalResult)) {
           if (!property_exists($evalResult, 'aggregate')) {
               $evalResult->aggregate = new stdClass();
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
   protected function evaluateFields_($rule, $submissionData)
   {
       //return Promise.reject("Testing static reject");

       // @type {Map.<{string} fieldName, {player.FieldEvalResult}>}
       var $outcomes = [];

       foreach ($rule as $statementKey)) {
           // Currently only 'whenHandler' is supported
           var $statementHandler = $this->handlers_[statementKey];

           try {
               // using primivite for construct so it can break
               if ($statementHandler && $rule[$statementKey]) {
                   // Accumulate outcomes.
                   $outcomes = array_merge(
                       $statementHandler->evaluate($rule[$statementKey], $submissionData));
               }
           } catch (Exception $error) {
               return $error;
           }
       }

       //console.log('outcomes=' + JSON.stringify(outcomes));
       return $outcomes;
   }
}
