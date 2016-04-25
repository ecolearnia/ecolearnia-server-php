<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;

use App\EcoLearnia\Modules\Assignment\Evaluation\DefaultEvaluator;
use App\EcoLearnia\Modules\Assignment\Evaluation\WhenHandler;

class DefaultEvaluatorEvaluationTest extends TestCase
{

    protected function setUp()
    {
        //$this->markTestSkipped('The Skipping.');
    }

    /**
     * testCalculateAttemptsOneSubmissionNoLeft
     */
    public function testEvaluateCorrect()
    {
        $defaultEvaluator = new DefaultEvaluator();
        $itemContent = $this->loadJson('addition.testitem.json');

        $activity = new stdClass();
        $activity->contentInstance = $itemContent;
        $activity->item_evalDetailsList = [
            ['submission'=>'MyFakeSubmission', 'evalResult'=>'MyFakeEvalResult']
        ];
        $activity->policy = ['maxAttempts' => 2];

        $submissionDetails = [
            'fields' =>[
                'field1' => ['value' => 20 ]
            ]
        ];

        $result = $defaultEvaluator->evaluate($activity, $submissionDetails);

        $expected = new \stdClass();
        $expected->fields = [
            'field1' => [
                'score' => 1,
                'feedback' => 'NUMBER Just right!!',
                'pass' => true
            ]
        ];
        $expected->attemptNum = 2;
        $expected->attemptsLeft = 0;
        $expected->aggregate = new \stdClass();
        $expected->aggregate->score = 1.0;
        $expected->aggregate->pass = true;

        $this->assertEquals($expected, $result, 'CalculateAttempts returned wrong values');
    }


    public function loadJson($filename)
    {
        $filedata = file_get_contents('tests/mock/' . $filename);
        $json = json_decode($filedata, true);
        return $json;
    }

}
