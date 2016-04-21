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
		parent::__construct('\\App\\EcoLearnia\\Modules\\Assignment\\Activity',['assignment', 'content']);
	}

    public function name()
    {
        return 'ActivityService';
    }

    /**
     * Add an activity to the assignment
     * @param string $assignmentUuid - the assginment's uuid
     * @param string $contentUuid - the content's uuid
     * @param array $contenInstance - the instantiated content (e.g. variables are set)
     */
    public function addActivity($assignmentUuid, $contentUuid, $contentInstance)
    {
        $model = $this->createNewModel();
        $model->assignmentUuid = $assignmentUuid;
        $model->contentInstance = $contentInstance;
        $model->sequenceNum = 0;
        $model->contentUuid = $contentUuid;

        $this->add($model);

        return $model;
    }


    /**
     * Counts the activities of an assignment that instantiated given content.
     * Remember, an assignmentUuid defines the user so no need to specify the user.
     *
     * @param string $assignmentUuid - the assignment Uuid
     * @param string $contentUuid - the content uuid
     */
    public function countByContent($assignmentUuid, $contentUuid)
    {
        $criteria = EcoCriteriaBuilder::conj(
            [
                EcoCriteriaBuilder::equals('assignmentUuid', $assignmentUuid),
                EcoCriteriaBuilder::equals('contentUuid', $contentUuid)
            ]
        );
        return $this->count($criteria);
    }
}
