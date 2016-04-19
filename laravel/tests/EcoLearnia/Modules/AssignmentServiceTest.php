<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\EcoCriteriaBuilder;

use App\EcoLearnia\Modules\Content\ContentService;
use App\EcoLearnia\Modules\Assignment\AssignmentService;

class AssignmentServiceTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewModel()
    {
        $svc = new AssignmentService();
        $input = self::createAssignmentInput('ABCD');
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Assignment model empty');

        $this->assertEquals($input['outsetCNodeUuid'], $model->outsetCNodeUuid, 'Assignment model outsetCNodeUuid different ');
    }

    /**
     * A basic functional test example.
     *
     * @return void
    public function testAddFindRemoveModel()
    {
        $svc = new AssignmentService();
        $model = self::addTestAssignment($svc);

        $result = $svc->findByPK($model->uuid);

        $count = $svc->removeByPK($model->uuid);

        $this->assertEquals(1, $count, 'Failed to remove one');

        $result2 = $svc->findByPK($model->uuid);
        $this->assertEmpty($result2, 'Assignment still exists after removal');

    }
    */


    /**
     * A basic functional test example.
     *
     * @return void
    public function testUpdateAssignment()
    {
        $svc = new AssignmentService();
        $model = self::addTestAssignment($svc);

        $newData = self::createAssignmentInput('TitleUpdated', ['data' => 'modified-data']);

        $svc->update($model->uuid, $newData);

        $retrieved = $svc->findByPK($model->uuid);

        $expectedAssignment = new stdClass;
        $expectedAssignment->data = 'modified-data';

        $this->assertEquals($newData['meta_title'] , $retrieved->meta_title);
        $this->assertEquals($expectedAssignment, $retrieved->Assignment);

        self::removeTestAssignment($svc, $model->uuid);
    }
    */

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testStartAssignment()
    {
        $svc = new AssignmentService();

        $contentSvc = new ContentService();
        $content = ContentServiceTest::addTestContent($contentSvc);
        $assignment = $svc->startAssignment($content->uuid);

        $this->assertEquals((string)$content->uuid, $assignment->getAttribute('outsetCNodeUuid'), 'outsetCnodeUUid is dfferent');

        // Teardown
        self::removeTestAssignment($svc, $assignment->uuid);
        ContentServiceTest::removeTestContent($contentSvc, $content->uuid);
    }

    /**
     * A basic functional test example.
     *
     * @return void
    public function testNextActivity()
    {
        $svc = new AssignmentService();

        $contentSvc = new ContentService();
        $content = ContentServiceTest::addTestContent($contentSvc);
        $assignment = $svc->startAssignment($content->uuid);

        $this->assertEquals((string)$content->uuid, $assignment->getAttribute('outsetCNodeUuid'), 'outsetCnodeUUid is dfferent');

        $activity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($activity), 'Activity is empty');

        //$this->assertEquals(

        self::removeTestAssignment($svc, $assignment->uuid);
        ContentServiceTest::removeTestContent($contentSvc, $content->uuid);
    }
    */


    // Auxiliary function_exists
    public static function addTestAssignment($svc,
        $outsetCNodeUuid, $config = [])
    {
        $input = self::createAssignmentInput($outsetCNodeUuid, $config);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }

    public static function removeTestAssignment($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }

    public static function createAssignmentInput($outsetCNodeUuid, $config = [])
    {
        $input = [
            'outsetCNodeUuid' => $outsetCNodeUuid,
            'config' => $config
        ];
        return $input;
    }
}
