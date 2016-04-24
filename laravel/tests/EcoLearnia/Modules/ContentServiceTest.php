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
        $model = self::addTestContent($svc, 'Test:Root', ['data' => 'testing'], null, 'item');

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
        $rootModel = self::addTestContent($svc, 'Test:Root', ['data' => 'testing'], null, 'node');
        $l1Model = self::addTestContent($svc, 'Test:Level1', ['data' => 'testing'], $rootModel->uuid, 'node');
        $itemModel = self::addTestContent($svc, 'Test:Item', ['data' => 'testing'], $l1Model->uuid, 'item');

        $firstItem = $svc->getFirstItem($rootModel->uuid);
        $this->assertTrue(!empty($firstItem), 'First Item is empty');
        $this->assertEquals($itemModel->meta_title, $firstItem->meta_title, 'Wrong first item retrieved');

        self::removeTestContent($svc, (string)$itemModel->uuid);
        self::removeTestContent($svc, (string)$l1Model->uuid);
        self::removeTestContent($svc, (string)$rootModel->uuid);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetNextItem()
    {
        $svc = new ContentService();
        $contents = self::addTestContentTree($svc, '');

        // Move 1 discance from item1
        $nextItem = $svc->getNextItem($contents['node1'], $contents['item1']->uuid);
        $this->assertTrue(!empty($nextItem), 'Next Item is empty');
        $this->assertEquals($contents['item2']->meta_title, $nextItem->meta_title, 'Wrong next item retrieved');

        // Move 1 discance from item2
        $nextItem = $svc->getNextItem($contents['node1'], $contents['item2']->uuid);
        $this->assertTrue(!empty($nextItem), 'Next Item is empty');
        $this->assertEquals($contents['item3']->meta_title, $nextItem->meta_title, 'Wrong next item retrieved');

        // Move 2 discance from item1
        $nextItem = $svc->getNextItem($contents['node1'], $contents['item1']->uuid, 2);
        $this->assertTrue(!empty($nextItem), 'Next Item is empty');
        $this->assertEquals($contents['item3']->meta_title, $nextItem->meta_title, 'Wrong next item retrieved');

        $nextItem = $svc->getNextItem($contents['node1'], $contents['item3']->uuid);
        $this->assertTrue(empty($nextItem), 'Next Item is not empty');

        self::removeTestContents($svc, $contents);
    }



    // Auxiliary function_exists

    /**
     * Creates test tree of content:
     * + root
     *   + node1
     *     + item1
     *     + item2
     *     + item3
     */
    public static function addTestContentTree($svc, $titlePrefix)
    {
        $root = self::addTestContent($svc, $titlePrefix . 'Test:Root', [], null, 'node');
        $node1 = self::addTestContent($svc, $titlePrefix . 'Test:Level1', [], $root->uuid, 'node');
        $item1 = self::addTestContent($svc, $titlePrefix . 'Test:Item1', ['data' => 'testing1'], $node1->uuid, 'item');
        $item2 = self::addTestContent($svc, $titlePrefix . 'Test:Item2', ['data' => 'testing2'], $node1->uuid, 'item');
        $item3 = self::addTestContent($svc, $titlePrefix . 'Test:Item3', ['data' => 'testing3'], $node1->uuid, 'item');

        return [
            'item3' => $item3, 'item2' => $item2, 'item1' => $item1, 'node1' => $node1, 'root'=>$root
        ];
    }

    public static function addTestContent($svc,
        $meta_title = 'TestTitle', $content = ['data' => 'testing'], $parentUuid = null, $type = 'node',
        $config = null, $realmUuid = 'TestRealm', $meta_locale = 'EN_us')
    {
        $input = self::createContentInput($meta_title, $content, $parentUuid, $type, $config, $realmUuid, $meta_locale);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }


    public static function removeTestContents($svc, $contents)
    {
        foreach ($contents as $content) {
            $svc->removeByPK($content->uuid);
        }
    }

    public static function removeTestContent($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }


    public static function createContentInput(
        $meta_title, $content, $parentUuid = null, $type = 'item',
        $config = null,
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
            'content' => $content,
            'config' => $config
        ];
        return $input;
    }
}
