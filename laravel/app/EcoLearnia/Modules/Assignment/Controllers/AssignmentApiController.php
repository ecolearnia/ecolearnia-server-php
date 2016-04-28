<?php

namespace App\EcoLearnia\Modules\Assignment\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\AbstractResourceApiController;

use App\EcoLearnia\Modules\Content\ContentService;
use App\EcoLearnia\Modules\Assignment\ActivityService;
use App\EcoLearnia\Modules\Assignment\AssignmentService;

/**
 * Assignment Resource API controller
 */
class AssignmentApiController extends AbstractResourceApiController
{
	protected $service = null;

	protected $contentService;
	protected $activityService;

	public function __construct() {
		parent::__construct(new AssignmentService);
		$this->contentService = new ContentService();
		$this->activityService = new ActivityService();
	}

	/**
	 * @overrides
	 */
	public function store(Request $request)
	{
		return $this->startAssignment($request);
	}

	/**
	 * Start (create) an assignment.
	 *
	 * @return Assignment
	 */
	public function startAssignment(Request $request)
	{
		$assignmentModel = null;
		$outsetNodeUuid = $request->input('outsetNode');
		if (empty($outsetNodeUuid))
		{
			return $this->jsonResponse(array('error' => 'Missing parameter outsetNode'), 400);
		}

		try {
			$assignmentModel = $this->service->startAssignment($outsetNodeUuid);
		} catch (Exception $e) {
			return $this->jsonResponse(array('error' => $e->getMessage()), 500);
		}

		return $this->jsonResponse($assignmentModel, 201);
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
		try {
			$nextActivity = $this->service->nextActivity($assignmentUuid);
		} catch (Exception $e) {
			return $this->jsonResponse(array('error' => $e->getMessage()), 500);
		}

		return $this->jsonResponse($nextActivity, 201);
	}


}
