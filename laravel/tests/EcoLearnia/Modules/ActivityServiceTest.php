<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\EcoCriteriaBuilder;

use App\EcoLearnia\Modules\Content\ContentService;
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
        $input = self::createActivityInput('ABCD');
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Activity model empty');

        $this->assertEquals($input['outsetCNodeUuid'], $model->outsetCNodeUuid, 'Activity model outsetCNodeUuid different ');
    }

    /**
     * A basic functional test example.
     *
     * @return void
    public function testAddFindRemoveModel()
    {
        $svc = new ActivityService();
        $model = self::addTestActivity($svc);

        $result = $svc->findByPK($model->uuid);

        $count = $svc->removeByPK($model->uuid);

        $this->assertEquals(1, $count, 'Failed to remove one');

        $result2 = $svc->findByPK($model->uuid);
        $this->assertEmpty($result2, 'Activity still exists after removal');

    }
    */


    /**
     * A basic functional test example.
     *
     * @return void
    public function testUpdateActivity()
    {
        $svc = new ActivityService();
        $model = self::addTestActivity($svc);

        $newData = self::createActivityInput('TitleUpdated', ['data' => 'modified-data']);

        $svc->update($model->uuid, $newData);

        $retrieved = $svc->findByPK($model->uuid);

        $expectedActivity = new stdClass;
        $expectedActivity->data = 'modified-data';

        $this->assertEquals($newData['meta_title'] , $retrieved->meta_title);
        $this->assertEquals($expectedActivity, $retrieved->Activity);

        self::removeTestActivity($svc, $model->uuid);
    }
    */



    // Auxiliary function_exists
    public static function addTestActivity($svc,
        $outsetCNodeUuid, $config = [])
    {
        $input = self::createActivityInput($outsetCNodeUuid, $config);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }

    public static function removeTestActivity($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }

    public static function createActivityInput($outsetCNodeUuid, $config = [])
    {
        $input = [
            'outsetCNodeUuid' => $outsetCNodeUuid,
            'config' => $config
        ];
        return $input;
    }
}
