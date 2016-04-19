<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\EcoLearnia\Modules\Content\ContentService;

class ContentServiceTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewModel()
    {
        $svc = new ContentService();
        $input = self::createContentInput('Test title', ['data' => 'testing']);
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Content model empty');

        $expectedContent = new stdClass;
        $expectedContent->data = 'testing';

        $this->assertTrue(!empty($model->uuid), 'Content uuid is not empty');
        $this->assertEquals('Test title', $model->meta_title, 'Content->title is different');

        $this->assertEquals($expectedContent, $model->content, 'Content->content is different');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAddFindRemoveModel()
    {
        $svc = new ContentService();
        $model = self::addTestContent($svc);

        $result = $svc->findByPK($model->uuid);

        $count = $svc->removeByPK($model->uuid);

        $this->assertEquals(1, $count, 'Failed to remove one');

        $result2 = $svc->findByPK($model->uuid);
        $this->assertEmpty($result2, 'Content still exists after removal');

    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testUpdateContent()
    {
        $svc = new ContentService();
        $model = self::addTestContent($svc);


        $newData = self::createContentInput('TitleUpdated', ['data' => 'modified-data']);
        $svc->update($model->uuid, $newData);
        /* Same as:
        $model->meta_title = 'TitleUpdated';
        $model->content = ['data' => 'modified-data'];
        $model->save();
        */

        $retrieved = $svc->findByPK($model->uuid);

        $expectedContent = new stdClass;
        $expectedContent->data = 'modified-data';

        $this->assertEquals($newData['meta_title'] , $retrieved->meta_title);
        $this->assertEquals($expectedContent, $retrieved->content);

        self::removeTestContent($svc, $model->uuid);
    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetFirstItem()
    {
        $svc = new ContentService();
        $rootModel = self::addTestContent($svc, 'Test:Root', ['data' => 'testing']);
        $l1Model = self::addTestContent($svc, 'Test:Level1', ['data' => 'testing'], $rootModel->uuid);
        $itemModel = self::addTestContent($svc, 'Test:Item', ['data' => 'testing'], $l1Model->uuid, 'item');

        $firstItem = $svc->getFirstItem($rootModel->uuid);
        $this->assertTrue(!empty($firstItem), 'First Item is empty');
        $this->assertEquals($itemModel->meta_title, $firstItem->meta_title, 'Wrong first item retrieved');

        $count = $svc->removeByPK($itemModel->uuid);
        /*
        self::removeTestContent($svc, (string)$itemModel->uuid);
        self::removeTestContent($svc, (string)$l1Model->uuid);
        self::removeTestContent($svc, (string)$rootModel->uuid);
        */
    }


    // Auxiliary function_exists
    public static function addTestContent($svc,
        $meta_title = 'TestTitle', $content = ['data' => 'testing'], $parentUuid = null, $type = 'node',
        $realmUuid = 'TestRealm', $meta_locale = 'EN_us')
    {
        $input = self::createContentInput($meta_title, $content, $parentUuid, $type, $realmUuid, $meta_locale);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }

    public static function removeTestContent($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }


    public static function createContentInput(
        $meta_title, $content, $parentUuid = null, $type = 'node',
        $realmUuid = 'TestRealm', $meta_locale = 'EN_us'
        )
    {
        $input = [
            'realmUuid' => $realmUuid,
            'parentUuid' => $parentUuid,
            'content' => $content,
            'type' => $type,
            'meta_locale' => $meta_locale,
            'meta_title' => $meta_title,
            'content' => $content
        ];
        return $input;
    }
}
