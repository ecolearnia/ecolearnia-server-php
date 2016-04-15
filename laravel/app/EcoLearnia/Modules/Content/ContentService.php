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
     * Returns a new instance of account model
     */
    public function newContent($array)
    {
        $model = new Content($array);
        $model->createdAt = new DateTime();

        return $model;
    }

    /**
     * @overrides  AbstractResourceService::createNewModel()
     * Creates a new model initializing the createdAt property
    public function createNewModel($data = null, $modelFqn = null)
    {
        $model = parent::createNewModel($data, $modelFqn);
    }
    */

    /**
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
     * updates the record by adding child reference
     * precondition: the uuid should be of an internal node
     */
    public function addChildTo($uuid, $childModel)
    {
        $cnode = $this->findByPK($uuid);
        if ($cnode->type == 'node') {
            return false;
        }
        print _r($cnode->content);
        if ($childModel->parentUuid) {
            // update child model
            $childModel->parentUuid = $uuid;
        }
        return true;
    }

}
