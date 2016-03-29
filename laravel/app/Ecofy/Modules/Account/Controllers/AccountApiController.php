<?php

namespace App\Ecofy\Modules\Account\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\AbstractResourceApiController;

// Ecofy service
use App\Ecofy\Modules\Account\AccountServiceContract;

/**
 * Account Resource API controller
 */
class AccountApiController extends AbstractResourceApiController
{
	protected $service = null;

	public function __construct(AccountServiceContract $accountService) {
		$this->service = $accountService;
	}

}
