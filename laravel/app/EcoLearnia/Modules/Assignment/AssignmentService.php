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
use App\EcoLearnia\Modules\Assignment\Activity;

use App\EcoLearnia\Modules\Content\ContentService;


class AssignmentService extends AbstractResourceService
{
    protected $activityService = null;
    protected $contentService = null;

    public function __construct() {
		parent::__construct('\\App\\EcoLearnia\\Modules\\Assignment\\Assignment',['outsetCNode', 'recentActivity']);
	}

    public function name()
    {
        return 'AssignmentService';
    }

    public function getContentService()
    {
        if ( $this->contentService == null) {
            //$this->contentService = \App::make('App\Ecofy\Modules\Content\ContentServiceContract');
            $this->contentService = new ContentService();
        }
        return $this->contentService;
    }

    public function getActivityService()
    {
        if ( $this->activityService == null) {
            $this->activityService = new ActivityService();
        }
        return $this->activityService;
    }

    /**
     * Creates a new model initializing the createdAt property
     * And generating a new uuid
     */
    public function createNewModel($data = null, $modelFqn = null)
    {
        $model = parent::createNewModel($data, $modelFqn);
        $model->lastInteraction = $model->createdAt;

        $model->stats_activitiesCount = 0;
        $model->stats_corrects = 0;
        $model->stats_incorrects = 0;
        $model->stats_partialcorrects = 0;
        $model->stats_score = 0;

        return $model;
    }


    /**
     * Start (create) an assignment.
     *
     * @return Assignment
     */
    public function startAssignment($outsetCnodeUUid)
    {
        $outsetNode = $this->getContentService()->findByPK($outsetCnodeUUid);

        if (empty($outsetNode))
        {
            throw new Exception('Unexisting outsetNode ' . $outsetCnodeUUid);
        }

        $assignmentModel = $this->createNewModel();
        $assignmentModel->outsetCNodeUuid = $outsetNode->uuid;

        $this->getContentService()->add($assignmentModel);

        return $assignmentModel;
    }

    /**
     * Create the next activity.
     * Empty if end of assignment reached.
     *
     * @return ActivityDescriptor
     */
    public function nextActivity($assignmentUuid)
    {

        $itemContent = null;  // The item content that should be used to instantiate the activity
        $nextActivity = null;
        $assignment = $this->findByPK($assignmentUuid);

        if (empty($assignment)) {
            throw new Exception("Assignment ID NotFound");
        }


        if (empty($assignment->activityTailUuid))
        {
            // This is a brand new assignment, add the next item
            $itemContent = $this->getContentService()->getFirstItem($assignment->outsetCNodeUuid);

            $assignment->status = 1;
        } else {
            // Get the activity tail and traverse through the associated content
            // to obtain the next content item.
            $activityTail = $this->getActivityService()->findByPK($assignment->activityTailUuid);

            if (!empty($activityTail->content->parentUuid))
            {
                $itemParentConfig = $activityTail->content->parent->config;
                if (!empty($itemParentConfig)) {
                    // until limit is reached
                    $repeatLimit = ObjectAccessor::get($itemParentConfig, 'repeat.limit', 1);
                    $count = $this->getActivityService()->countByContent($assignment->uuid, $activityTail->content->uuid);
                    if ($count < $repeatLimit) {
                        // Hasn't reached the limit, use the same content
                        $itemContent = $activityTail->content;
                    }
                }
                if (empty($itemContent)) {
                    $itemContent = $this->getContentService()->getNextItem(
                        $assignment->outsetCNodeUuid, $activityTail->content->uuid);
                }
            } else {
                throw new Exception("The activity's content does not have a parent node");
            }
        }

        if (empty($itemContent)) {
            // Reach the end of the assignment, no more activity
            return null;
        }
        $contentInstance = $this->instantiateContent($assignment, $itemContent);
        $nextActivity = $this->getActivityService()->addActivity(
                $assignment->uuid, $itemContent->uuid, $contentInstance,
                $assignment->stats_activitiesCount++
            );
        if (empty($assignment->activityHeadUuid)){
            $assignment->activityHeadUuid = $nextActivity->uuid;
        }
        $assignment->activityTailUuid = $nextActivity->uuid;
        $assignment->save();

        return $nextActivity;
    }

    /**
     * Instantiates content
     * @param Assignment $assignment
     * @param Content $itemContent
     * @return Content - the instantiated conten
     */
    public function instantiateContent($assignment, $itemContent)
    {
        // Create a new Activity and assignt it as head
        $contentInstance = $itemContent->content;
        $beforeInstantiation = ObjectAccessor::get($itemContent->content, 'middleware.beforeInstantiation');

        //var_dump($itemContent->content);
        //var_dump($beforeInstantiation);
        //die();

        if (empty($beforeInstantiation) && !empty($itemContent->parent->config)) {
            $beforeInstantiation = ObjectAccessor::get($itemContent->parent->config, 'middleware.beforeInstantiation');
        }

        if (!empty($beforeInstantiation)) {
            $className = '\\App\\EcoLearnia\\Modules\\Assignment\\Middleware\\' . $beforeInstantiation->class;

            $middlware = new $className;
            $params = [
                'stats' => $assignment->getStats(),
                'params' => $beforeInstantiation->params
            ];
            $contentInstance = $middlware->apply($itemContent->content, $params);
        }

        return $contentInstance;
    }

    /**
     * Updates the state_itemEvalBriefs fields
     * @todo - UNIT TEST PENDING
     *
     * @param string $assignmentUuid
     * @param string $activityUuid
     * @param Object $evalDetails
     */
    public function updateEvalBriefs($assignmentUuid, $activityUuid, $evalDetails)
    {
        $assignment = $this->findByPK($assignmentUuid);
        if (empty($assignment->state_itemEvalBriefs)) {
            $assignment->state_itemEvalBriefs = [];
        }

        /*
         * state_itemEvalBriefs {{
         *      {
         *            activityId: activityId,
         *            attemptNum: attemptNum,
         *            secondsSpent: secondsSpent,
         *            aggregateResult: aggregateResult
         *      }
         * }}
         *
         */
        $length = count($assignment->state_itemEvalBriefs);
        for($i=0; $i < $length; $i++ ) {
            $evalBrief = new stdClass();
            $evalBrief->attemptNum = $evalDetails->evalResult->attemptNum;
            $evalBrief->secondsSpent = $evalDetails->submission->secondsSpent;
            $evalBrief->aggregateResult = $evalDetails->evalResult->aggregate;
            if ($assignment->state_itemEvalBriefs[$i]->activityId == $activityUuid) {
                $assignment->state_itemEvalBriefs[$i] = $evalBrief;
            } else {
                array_push($assignment->state_itemEvalBriefs, $evalBrief);
            }
        }
        $assignment->save();
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

        // @todo - make it transactional
        $activityDelCriteria = EcoCriteriaBuilder::equals('assignmentUuid', $match->uuid);
        $activities = $this->getActivityService()->query($activityDelCriteria);
        foreach($activities as &$activity)
        {
            $activity->delete();
        }
        $match->delete();
    }


}
