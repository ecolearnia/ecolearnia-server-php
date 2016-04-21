<?php

namespace App\EcoLearnia\Modules\Content;

use DateTime;
use Log;
use DB;

use \Ramsey\Uuid\Uuid;
use \Firebase\JWT\JWT;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Ecofy\Support\AbstractResourceService;

// Models
use App\Ecofy\Modules\Account\Account;

use App\EcoLearnia\Modules\Content\ContentServiceContract;


class ContentService extends AbstractResourceService
    implements ContentServiceContract
{
    protected $accountService = null;

    public function __construct() {
		parent::__construct('\\App\\EcoLearnia\\Modules\\Content\\Content',['parent', 'children']);
	}

    public function name()
    {
        return 'ContentService';
    }

    /**
     * Returns the first leaf node
     * @param Content an internal node
     */
    public function getFirstItem($nodeUuid)
    {
        if (empty($nodeUuid)) {
            return null;
        }
        $currNode = $this->findByPK($nodeUuid);

        while ($currNode->type == 'node') {
            if (!empty($currNode->content) && is_array($currNode->content)) {
                $firstChildUuid = $currNode->content[0];
                $currNode = $this->findByPK($firstChildUuid);

                if(empty($currNode)) {
                    throw new Exception('CNode [' . $firtChildUuid . '] not found');
                }
            }
        }

        if ($currNode->type != 'item') {
            throw new Exception('First leaf is not an item');
        }
        return $currNode;
    }


    /**
     * Returns the next item starting from $initialItemUuid
     * @param Content $outsetNodeUuid the node which is the root.
     *                When the next reaches this node it has reached the end of
     *                the assignment. (NOT IMPLEMNENTED YET)
     * @param string $initialItemUuid - the uuid of the item to start from.
     * @param integer $distance - The distance to move
     */
    public function getNextItem($outsetNodeUuid, $initialItemUuid, $distance = 1)
    {
        if (empty($initialItemUuid)) {
            return null;
        }

        $nextItem = null;
        $currItem = $this->findByPK($initialItemUuid);

        $index = array_search($initialItemUuid, $currItem->parent->content);
        if ($index < count($currItem->parent->content) - $distance ) {
            $nextItemUuid = $currItem->parent->content[$index + $distance];
            $nextItem = $this->findByPK($nextItemUuid);
        } else if ($index >= count($currItem->parent->content) - $distance ) {
            // last element reached.
            // For the mean time, return null;
            // @todo - When reached the parent's last child, go one parent up
            //         and get the first child.
        }
        return $nextItem;
    }


    /**
     * @override
     * Add
     * The method calls $this->createNewModel() and saves it.
     * The derived class can either override the createNewModel()
     * to modify the behavior of serializing array data into the model.
     *
     * @param Object  $resource - The resource (record) to add
     * @param Object  $options  - Any options for add operation
     * @return Model  - Upon success, return the added model
     */
    public function add($resource, $options = null)
    {
        $model = parent::add($resource, $options);

        if (!empty($model->parentUuid)) {
            $this->addChildTo($model->parentUuid, $model);
        }

        return $model;
    }

    /**
     * @override
     * Find
     * URL example: http://localhost:8000/api/contents/11a2ddfb-4688-4697-a21c-abf6eefd6f4a?traverse=1
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for find operation
     * @return Model  - Upon success the model returned
     */
    public function find($criteria, $options = null)
    {
        $model = parent::find($criteria, $options);
        // @todo re-order children by the order given in the content array

        if (!empty($options['traverse']) && $options['traverse'] == '1') {
            $this->buildDescendant($model, $options);
        }

        return $model;
    }

    /**
     * @override
     * Remove only one record, the first matching one.
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function remove($criteria, $options = null)
    {
        $query = $this->buildQuery($criteria);
        $match = $query->first();

        if (empty($match)) {
            return 0;
        }

        //print('Title: "' . $match->meta_title . '"');
        //print('Type: "' . $match->type . '"');

        if ($match->type != 'item') {
            // is an internal node, check weather the body is empty.
            if (count($match->content) > 0) {

                throw new \Exception('Cannot remove node with children');
            }
        }

        // @todo - Make it transactional

        if (!empty($match->parentUuid)) {
            // Update parent's content that references to this node
            $this->removeChildFrom($match->parentUuid, $match);
        }
        $deletedRows = $match->delete();

        return $deletedRows;
    }

    /**
     * Use recurson to build descendants of the tree
     * @param {Content} $model - the node to start tree reconstruction
     * @param {Content}  the same model which children populated
     */
    protected function buildDescendant($model, $options)
    {
        // The find() will automatically populate children
        // Further recurssion will require fetching the children.
        if (empty($model->children)) {
            $criteriaByParent = [
                'var' => 'parentUuid',
                'op'  => '=',
                'val' => $model->uuid
            ];
            $children = parent::query($criteriaByParent, $options);
            // @todo re-order children by the order given in the content array
            $model->children = $children;
        }

        foreach ($model->children as $child) {
            $this->buildDescendant($child, $options);
        }

        return $model;
    }

    /**
     * updates the record by adding child reference
     * precondition: the uuid should be of an internal node
     */
    public function addChildTo($parentUuid, $childModel, $index = -1)
    {
        $parentNode = $this->findByPK($parentUuid);

        $parentNode->addChildUuid($childModel->uuid, $index);
        $parentNode->save();

        if ($childModel->parentUuid) {
            // update child model
            $childModel->parentUuid = $parentUuid;
        }
        return true;
    }

    /**
     * updates the record by adding child reference
     * precondition: the uuid should be of an internal node
     */
    public function removeChildFrom($parentUuid, $childModel)
    {
        if (empty($parentUuid)) {
            return false;
        }
        $parentNode = $this->findByPK($parentUuid);

        if ($parentNode->type != 'node') {
            return false;
        }
        $content = $parentNode->content;

        if (empty($content) || !is_array($content))
        {
            return false;
        } else {
            $idx = array_search($childModel->uuid, $content);
            array_splice($content, $idx, 1);
        }
        $parentNode->content = $content;
        $parentNode->save();

        if ($childModel->parentUuid) {
            // update child model
            $childModel->parentUuid = null;
        }
        return true;
    }

}
