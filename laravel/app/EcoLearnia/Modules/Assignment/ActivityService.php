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
     * @param number $sequenceNum - the sequence number within the assignment
     */
    public function addActivity($assignmentUuid, $contentUuid, $contentInstance, $sequenceNum)
    {
        $model = $this->createNewModel();
        $model->assignmentUuid = $assignmentUuid;
        $model->contentInstance = $contentInstance;
        $model->contentUuid = $contentUuid;
        $model->sequenceNum = $sequenceNum;

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

    /**
     * Saves the state of the item in the activity
     *
     * @param string $uuid - the activity Uuid
     * @param array $state - the state of the item uuid
     * @param array $timestamps - (optional) the timestamps
     */
    public function saveState($uuid, $state, $timestamps = null)
    {
        // @todo - the structure has to be same as in the interactive's
        // player.ActivityDetails
        $data = array();
        $data['item_state'] = $state;
        $data['item_timestamps'] = $timestamps;

        return $this->update($uuid, $data);
    }

    /**
     * Saves the evaluation of a submission
     *
     * @param string $uuid - the activity Uuid
     * @param array $evalDetails - the state of the item uuid
     * @param array $timestamps - (optional) the timestamps
     */
    public function evaluate($uuid, $submissionDetails)
    {
        // 1. Retrieve the activity
        $activity = $this->findByPK($uuid);

        $activity->contentInstance;

    }

    /**
     * Saves the evaluation of a submission
     *
     * @param string $uuid - the activity Uuid
     * @param array $evalDetails - the state of the item uuid
     * @param array $timestamps - (optional) the timestamps
     */
    public function saveEvaluation($uuid, $evalDetails, $timestamps = null)
    {
        $activity = $this->findByPK($uuid);
        $evalDetailsList = $activity->evalDetails;
        if (empty($evalDetailsList)) {
            $evalDetailsList = [];
        }
        array_push($evalDetailsList, $evalDetails);

        $data['item_state'] = $evalDetails['submission']['fields'];
        $data['item_evalDetailsList'] = $evalDetailsList;
        $data['item_timestamps'] = $timestamps;
        return $this->update($uuid, $data);
    }
}
