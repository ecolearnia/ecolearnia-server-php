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
        $nextActivity = null;
        $assignment = $this->findByPK($assignmentUuid);

        if (empty($assignment->activityTailUuid))
        {
            // This is a barnd new assignment, add the next item
            $citem = $this->getContentService()->getFirstItem($assignment->outsetCNode);

            // Create a new Activity and assignt it as head
            $contentInstante = $citem->content;
            if (!empty($content->parent->config))
            {
                $beforeInstantiation = $content->parent->config['middleware']['beforeInstantiation'];
                // @odo - Instantiate the middleware and apply on the content for the instantiateion
            }
            $nextActivity = $this->getActivityService()->addActivity($assignment->uuid, $citem->uuid, $contentInstante);

            $assignment->activityHeadUuid = $nextActivity->uuid;
            $assignment->activityTailUuid = $nextActivity->uuid;
            $assignment->recentActivityUuid = $nextActivity->uuid;
            $assignment->status = 1;
        } else {
            // Get the activity tail and traverse through the associated content
            // to obtain the next content item.
            $activityTail = $this->getActivityService()->findByPK($assignment->activityTailUuid);

            if (!empty($activityTail->content->parentUuid))
            {
                $parentContent = $this->getContentService()->findByPK($activityTail->content->parentUuid);

                //$parentContent->content;
            }
        }
        return $nextActivity;
    }

}
