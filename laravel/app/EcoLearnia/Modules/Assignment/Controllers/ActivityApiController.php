<?php

namespace App\EcoLearnia\Modules\Assignment\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\AbstractNestedResourceApiController;

use App\EcoLearnia\Modules\Content\ContentService;
use App\EcoLearnia\Modules\Assignment\ActivityService;
use App\EcoLearnia\Modules\Assignment\AssignmentService;

/**
 * Assignment Resource API controller
 */
class ActivityApiController extends AbstractNestedResourceApiController
{
	protected $service = null;

	protected $contentService;
	protected $assignmentService;

	public function __construct() {
		parent::__construct(new ActivityService, ['assignmentUuid', 'uuid', 'uuid1', 'uuid2']);
		$this->contentService = new ContentService();
		$this->assignmentService = new AssignmentService();
	}

	/**
	 * Update Activity's item state
	 *
	 * @return Assignment
	 */
	public function saveState($assignmentUuid, $activityUuid, Request $request)
	{
		$state = $request->input('state');
		$timestamps = $request->input('timestamps');

		$result = null;
		try {
			$result = $this->service->saveState($activityUuid, $state, $timestamps);
		} catch (Exception $e) {
			return $this->jsonResponse(array('error' => $e->getMessage()), 500);
		}

		return $this->jsonResponse($result, 200);
	}

	/**
	 * Evaluate the submission
	 *
	 * @return Assignment
	 */
	public function evaluate($assignmentUuid, $activityUuid, Request $request)
	{
		$evalDetails = null;

		//$submissionDetails = $request->input('submissionDetails');
		/*
		$submissionDetails = $request->json();
        print_r($submissionDetails);
		*/
		$submissionDetails = $request->all();
		//print_r($all);
		//die();

		try {
			$evalDetails = $this->service->evaluate($activityUuid, $submissionDetails);
		} catch (Exception $e) {
			return $this->jsonResponse(array('error' => $e->getMessage()), 500);
		}

		return $this->jsonResponse($evalDetails, 200);
	}

}
