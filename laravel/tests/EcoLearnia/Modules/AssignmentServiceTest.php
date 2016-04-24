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
     * Test testStartAssignment()
     *
     * @return void
     */
    public function testStartAssignment()
    {
        $svc = new AssignmentService();

        $contentSvc = new ContentService();
        $content = ContentServiceTest::addTestContent($contentSvc, 'Test:testStartAssignment', []);
        $assignment = $svc->startAssignment($content->uuid);

        $this->assertEquals((string)$content->uuid, $assignment->getAttribute('outsetCNodeUuid'), 'outsetCnodeUUid is dfferent');

        // Teardown
        self::removeTestAssignment($svc, $assignment->uuid);
        ContentServiceTest::removeTestContent($contentSvc, $content->uuid);
    }

    /**
     * Test nextActivity()
     *
     * @return void
     */
    public function testNextActivity()
    {
        $svc = new AssignmentService();

        $contentSvc = new ContentService();
        $contents = ContentServiceTest::addTestContentTree($contentSvc, 'Assignment');

        $assignment = $svc->startAssignment($contents['node1']->uuid);

        $this->assertEquals((string)$contents['node1']->uuid, $assignment->outsetCNodeUuid, 'outsetCnodeUUid is dfferent');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($nextActivity), '1: Next activity is empty');
        $this->assertEquals($contents['item1']->meta_title, $nextActivity->content->meta_title, 'First Next activity has wrong content');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($nextActivity), '2: Next activity is empty');
        $this->assertEquals($contents['item2']->meta_title, $nextActivity->content->meta_title, 'Second Next activity has wrong content');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($nextActivity), '3:Next activity is empty');
        $this->assertEquals($contents['item3']->meta_title, $nextActivity->content->meta_title, 'Third Next activity has wrong content');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(empty($nextActivity), '1: Next activity is NOT empty');

        // $svc->removeByPK($assignment->uuid);
        $svc->removeByPK($assignment->uuid);

        self::removeTestAssignment($svc, $assignment->uuid);
        ContentServiceTest::removeTestContents($contentSvc, $contents);
    }

    /**
     * Tests the nextActivity() where the content node is configured to repeat
     *
     * @return void
     */
    public function testNextActivityWithRepetition()
    {
        $svc = new AssignmentService();

        $contentSvc = new ContentService();
        $root = ContentServiceTest::addTestContent($contentSvc, 'Test:Root', ['data' => 'testing'], null, 'node');
        $nodeConfig = ['repeat' => ['limit' => 2 ]];
        $node1 = ContentServiceTest::addTestContent($contentSvc, 'Test:Level1', ['data' => 'testing'], $root->uuid, 'node', $nodeConfig);
        $item = ContentServiceTest::addTestContent($contentSvc, 'Test:Item', ['data' => 'testing'], $node1->uuid, 'item');

        $assignment = $svc->startAssignment($node1->uuid);
        $this->assertEquals((string)$node1->uuid, $assignment->outsetCNodeUuid, 'outsetCnodeUUid is dfferent');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($nextActivity), '1: Next activity is empty');
        $this->assertEquals($item->meta_title, $nextActivity->content->meta_title, 'First Next activity has wrong content');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(!empty($nextActivity), '2: Next activity is empty');
        $this->assertEquals($item->meta_title, $nextActivity->content->meta_title, 'First Next activity has wrong content');

        $nextActivity = $svc->nextActivity($assignment->uuid);
        $this->assertTrue(empty($nextActivity), '3: Next activity is NOT empty');

        self::removeTestAssignment($svc, $assignment->uuid);

        ContentServiceTest::removeTestContent($contentSvc, (string)$item->uuid);
        ContentServiceTest::removeTestContent($contentSvc, (string)$node1->uuid);
        ContentServiceTest::removeTestContent($contentSvc, (string)$root->uuid);
    }


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
