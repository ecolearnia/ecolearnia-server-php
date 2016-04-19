<?php

namespace App\EcoLearnia\Modules\Assignment;

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

use App\Ecofy\Modules\Activity\ActivityServiceContract;


class ActivityService extends AbstractResourceService
{
    public function __construct() {
		parent::__construct('\\App\\EcoLearnia\\Modules\\Activity\\Activity',['assignment', 'content']);
	}

    public function name()
    {
        return 'ActivityService';
    }


    public function addActivity($assignmentUuid, $contentUuid, $contenInstance)
    {
        $model = $this->createNewModel();
        $model->assignmentUuid = $assignmentUuid;
        $model->contentInstance = $contentInstance;
        $model->sequenceNum = 0;
        $model->contentUuid = $contentUuid;

        $this->add($model);

        return $model;
    }

}
