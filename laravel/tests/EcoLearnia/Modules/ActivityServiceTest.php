<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\ObjectHelper;
use App\Ecofy\Support\EcoCriteriaBuilder;

use App\EcoLearnia\Modules\Content\ContentService;
use App\EcoLearnia\Modules\Assignment\AssignmentService;
use App\EcoLearnia\Modules\Assignment\ActivityService;

class ActivityServiceTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewModel()
    {
        $svc = new ActivityService();
        $input = self::createActivityInput('ABCD', 1, '123');
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Activity model empty');

        $this->assertEquals($input['assignmentUuid'], $model->assignmentUuid, 'Activity model assignmentUuid is different ');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAddFindCountModel()
    {
        $svc = new ActivityService();

        $contentSvc = new ContentService();
        $assginmentSvc = new AssignmentService();

        // Create a content in order to create an assigment
        // And then create an activity
        $contents = ContentServiceTest::addTestContentTree($contentSvc, 'Activity');
        $assignment = $assginmentSvc->startAssignment($contents['node1']->uuid);

        $activity = self::addTestActivity($svc, $assignment->uuid, $contents['item1']->uuid, 1);

        $result = $svc->findByPK($activity->uuid);
        $this->assertTrue(!empty($result), 'Activity find is null');
        $count = $svc->countByContent($assignment->uuid, $contents['item1']->uuid);
        $this->assertEquals(1, $count, 'Activity of assignment/content is not 1');

        // Test saveState
        $state = [
            'fields' => [ 'data' => 'mydata']
        ];
        $timestamps = [
            [ 'startTime' => '2016-04-22T22:44:27+00:00']
        ];

        $svc->saveState( (string)$activity->uuid, $state, $timestamps);
        $result = $svc->findByPK($activity->uuid);

        $expectedState = ObjectHelper::createObject($state);
        $this->assertEquals($expectedState, $result->item_state, 'Activity state does not match');

        $expectedTimestamps = ObjectHelper::createObject($timestamps);
        $this->assertEquals($expectedTimestamps, $result->item_timestamps, 'Activity timestamps does not match');

        // Test saveEvaluation
        $evalDetails = [
            'submission' => [
                'fields' => ['field1' => 'data1']
            ],
            'evalResult' => ['attemptNum' => 1]
        ];

        $svc->saveEvaluation( (string)$activity->uuid, $evalDetails, $timestamps);
        $result = $svc->findByPK($activity->uuid);
        $expectedState = ObjectHelper::createObject($evalDetails['submission']['fields']);
        $this->assertEquals($expectedState, $result->item_state, 'Activity state after saveEvaluation does not match');

        $expectedEvalDetailsList = ObjectHelper::createObject([$evalDetails]);
        $this->assertEquals($expectedEvalDetailsList, $result->item_evalDetailsList, 'Activity state after saveEvaluation does not match');

        // Test countByContent
        $activity2 = self::addTestActivity($svc, $assignment->uuid, $contents['item1']->uuid, 1);
        $count = $svc->countByContent($assignment->uuid, $contents['item1']->uuid);
        $this->assertEquals(2, $count, 'Activity of assignment/content is not 2');

        AssignmentServiceTest::removeTestAssignment($assginmentSvc, $assignment->uuid);
        ContentServiceTest::removeTestContents($contentSvc, $contents);
    }



    // Auxiliary function_exists
    public static function addTestActivity($svc,
        $assignmentUuid, $contentUuid, $sequenceNum, $contentInstance = [ 'body' => 'data'])
    {
        $input = self::createActivityInput($assignmentUuid,
            $contentUuid, $sequenceNum, $contentInstance);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }

    public static function removeTestActivity($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }

    public static function createActivityInput($assignmentUuid,
        $contentUuid, $sequenceNum, $contentInstance = [ 'body' => 'data'])
    {
        $input = [
            'assignmentUuid' => $assignmentUuid,
            'sequenceNum' => $sequenceNum,
            'contentUuid' => $contentUuid,
            'contentInstance' => $contentInstance
        ];
        return $input;
    }
}
