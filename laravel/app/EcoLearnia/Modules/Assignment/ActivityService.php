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

use App\EcoLearnia\Modules\Assignment\Evaluation\DefaultEvaluator;

class ActivityService extends AbstractResourceService
{
    protected $assignmentService = null;

    public function __construct() {
		parent::__construct('\\App\\EcoLearnia\\Modules\\Assignment\\Activity',['assignment', 'content']);
	}

    public function name()
    {
        return 'ActivityService';
    }

    public function getAssignmentService()
    {
        if ( $this->assignmentService == null) {
            $this->assignmentService = new AssignmentService();
        }
        return $this->assignmentService;
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
        $data['item_state'] = [
            '@type' => 'evaluation',
            'data' => $state
        ];
        $data['item_timestamps'] = $timestamps;

        return $this->update($uuid, $data);
    }

    /**
     * Saves the evaluation of a submission
     *
     * @param string $uuid - the activity Uuid
     * @param array $evalDetails - the state of the item uuid
     * @param array $timestamps - (optional) the timestamps
     * @return EvalResult
     */
    public function evaluate($uuid, $submissionDetails)
    {
        $activity = $this->findByPK($uuid);

        if (empty($activity)) {
            throw new Exception('Activity Not Found');
        }
        $evalutor = new DefaultEvaluator();
        $evalResult = $evalutor->evaluate($activity, $submissionDetails);

        $this->saveEvaluation($uuid, $evalResult, $submissionDetails);

        return $evalResult;
    }

    /**
     * Saves the evaluation of a submission
     *
     * @param string $uuid - the activity Uuid
     * @param array $evalResult - the evaluation resulted from evalutor->evaluate
     * @param array $timestamps - (optional) the timestamps
     */
    public function saveEvaluation($uuid, $evalResult, $submissionDetails, $timestamps = null)
    {
        $activity = $this->findByPK($uuid);

        $evalDetails = [
            'submission' => $submissionDetails,
            'evalResult' => (array)$evalResult
        ];

        $evalDetailsList = $activity->item_evalDetailsList;
        if (empty($evalDetailsList)) {
            $evalDetailsList = [];
        }
        array_push($evalDetailsList, $evalDetails);

        //print_r($submissionDetails['fields']);
        //die();

        $data['item_state'] = [
            '@type' => 'evaluation',
            'data' => $submissionDetails
        ];
        $data['item_evalDetailsList'] = $evalDetailsList;
        $data['item_timestamps'] = $timestamps;


        // @todo - update assignment's stats:
        // How to calculate for those activities tha has more than one attempt??
        // - increment stats_activitiesCount,
        // - calculate stats_timeSpent
        // - increment one of stats_corrects, stats_incorrects, stats_partialcorrects
        // - accumulate stats_score
        //$evalDatails->aggregate
        $this->getAssignmentService()->updateEvalBriefs($activity->assignmentUuid, $uuid, $evalDetails);

        return $this->update($uuid, $data);
    }
}
