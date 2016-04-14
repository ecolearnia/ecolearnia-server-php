<?php

namespace App\EcoLearnia\Modules\Content\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\AbstractResourceApiController;

// Interface
use App\EcoLearnia\Modules\Content\ContentService;

/**
 * Content Resource API controller
 */
class ContentApiController extends AbstractResourceApiController
{
	protected $service = null;

	public function __construct() {
		parent::__construct(new ContentService);
	}

}
