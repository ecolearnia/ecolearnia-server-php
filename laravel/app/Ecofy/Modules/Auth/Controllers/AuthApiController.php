<?php

namespace App\Ecofy\Modules\Auth\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\AbstractResourceApiController;

use App\Ecofy\Modules\Auth\AuthServiceContract;

/**
 * Auth Resource API controller
 */
class AuthApiController extends AbstractResourceApiController
{
	public function __construct(AuthServiceContract $authService) {
		parent::__construct($authService);
	}

}
