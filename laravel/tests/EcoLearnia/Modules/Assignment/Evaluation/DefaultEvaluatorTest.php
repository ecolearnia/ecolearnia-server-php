<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;

use App\EcoLearnia\Modules\Assignment\Evaluation\DefaultEvaluator;
use App\EcoLearnia\Modules\Assignment\Evaluation\WhenHandler;

class DefaultEvaluatorTest extends TestCase
{

    protected function setUp()
    {
        //$this->markTestSkipped('The Skipping.');
    }

    /**
     * testCalculateAttemptsNoSubmissions
     */
    public function testCalculateAttemptsNoSubmissions()
    {
        $defaultEvaluator = new DefaultEvaluator();

        $activity = new stdClass();
        $activity->item_evalDetailsList = [];
        $activity->policy = ['maxAttempts' => 1];
        $result = $defaultEvaluator->calculateAttempts_($activity);

        $expected = new stdClass();
        $expected->numAttempted = 0;
        $expected->attemptsLeft = 1;

        $this->assertEquals($expected, $result, 'CalculateAttempts returned wrong values');
    }

    /**
     * testCalculateAttemptsOneSubmissionNoLeft
     */
    public function testCalculateAttemptsOneSubmissionNoLeft()
    {
        $defaultEvaluator = new DefaultEvaluator();

        $activity = new stdClass();
        $activity->item_evalDetailsList = [
            ['submission'=>'MyFakeSubmission', 'evalResult'=>'MyFakeEvalResult']
        ];
        $activity->policy = ['maxAttempts' => 1];
        $result = $defaultEvaluator->calculateAttempts_($activity);

        $expected = new stdClass();
        $expected->numAttempted = 1;
        $expected->attemptsLeft = 0;

        $this->assertEquals($expected, $result, 'CalculateAttempts returned wrong values');
    }

    /**
     * testCalculateAttemptsTwoSubmissionOneLeft
     */
    public function testCalculateAttemptsTwoSubmissionOneLeft()
    {
        $defaultEvaluator = new DefaultEvaluator();

        $activity = new stdClass();
        $activity->item_evalDetailsList = [
            ['submission'=>'MyFakeSubmission', 'evalResult'=>'MyFakeEvalResult'],
            ['submission'=>'MyFakeSubmission', 'evalResult'=>'MyFakeEvalResult']
        ];
        $activity->contentInstance = ['defaultPolicy' => 3];
        $result = $defaultEvaluator->calculateAttempts_($activity);

        $expected = new stdClass();
        $expected->numAttempted = 2;
        $expected->attemptsLeft = 1;

        $this->assertEquals($expected, $result, 'CalculateAttempts returned wrong values');
    }


    /**
     * testCombineSubmissionData
     */
    public function testCombineSubmissionData()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('numbergame.testitem.json');

        $variableDeclarations = $itemContent['variableDeclarations'];
        $submissionData = [
            'subm1' => 'MyData'
        ];

        $result = $defaultEvaluator->combineSubmissionData_($variableDeclarations, $submissionData);

        $expected = [
            'var_num1' => 2,
            'var_num2' => 4,
            'var_num3' => 8,
            'var_patternIndex' => 9,
            'subm1' => 'MyData'
        ];

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');
    }

    /**
     * testCalculateAgregate
     */
    public function testCalculateAgregatePass()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('numbergame.testitem.json');

        $evalResult = new stdClass();
        $evalResult->fields = [
            'field1' => ['score' => 1],
            'field2' => ['score' => 1]
        ];
        $attemptNum = 1;

        $result = $defaultEvaluator->calculateAggregate_($evalResult, $attemptNum);

        $expected = new \stdClass();
        $expected->fields = [
            'field1' => ['score' => 1, 'pass' => true],
            'field2' => ['score' => 1, 'pass' => true]
        ];
        $expected->aggregate = new \stdClass();
        $expected->aggregate->score = 1;
        $expected->aggregate->pass = true;

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');

    }

    /**
     * testCalculateAgregateNoPass
     */
    public function testCalculateAgregateNoPass()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('numbergame.testitem.json');

        $evalResult = new \stdClass();
        $evalResult->fields = [
            'field1' => ['score' => 1.0],
            'field2' => ['score' => 0.8]
        ];
        $attemptNum = 1;

        $result = $defaultEvaluator->calculateAggregate_($evalResult, $attemptNum);

        $expected = new \stdClass();
        $expected->fields = [
            'field1' => ['score' => 1.0, 'pass' => true],
            'field2' => ['score' => 0.8, 'pass' => false]
        ];
        $expected->aggregate = new stdClass();
        $expected->aggregate->score = 0.90;
        $expected->aggregate->pass = false;

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');

    }

    /**
     * testEvaluateFieldsCorrect
     */
    public function testEvaluateFieldsCorrect()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('addition.testitem.json');

        $rule = $itemContent['responseProcessing'];
        $submissionData = [
            'field1_value' => 20,
            'var_num1' => 5,
            'var_num2' => 15
        ];

        $result = $defaultEvaluator->evaluateFields_($rule, $submissionData);

        $expected = [
            'field1' => [
                'score' => 1,
                'feedback' => 'NUMBER Just right!!'
            ]
        ];

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');
    }

    /**
     * testEvaluateFieldsCorrect
     */
    public function testEvaluateFieldsTooBig()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('addition.testitem.json');

        $rule = $itemContent['responseProcessing'];
        $submissionData = [
            'field1_value' => 20,
            'var_num1' => 3,
            'var_num2' => 4
        ];

        $result = $defaultEvaluator->evaluateFields_($rule, $submissionData);

        $expected = [
            'field1' => [
                'score' => 0,
                'feedback' => 'NUMBER TOO BIG'
            ]
        ];

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');
    }

    /**
     * testEvaluateFieldsCorrect
     */
    public function testEvaluateFieldsTooSmall()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('addition.testitem.json');

        $rule = $itemContent['responseProcessing'];
        $submissionData = [
            'field1_value' => 3,
            'var_num1' => 3,
            'var_num2' => 4
        ];

        $result = $defaultEvaluator->evaluateFields_($rule, $submissionData);

        $expected = [
            'field1' => [
                'score' => 0,
                'feedback' => 'NUMBER TOO SMALL'
            ]
        ];

        $this->assertEquals($expected, $result, 'combineSubmissionData returned wrong values');
    }

    public function loadJson($filename)
    {
        $filedata = file_get_contents('tests/mock/' . $filename);
        $json = json_decode($filedata, true);
        return $json;
    }

}
