<?php
namespace App\Ecofy\Support;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class AbstractBaseController extends BaseController
{
	/**
	 * Returns the json response with the given status
	 * @param {string | array} $data
	 */
	protected function jsonResponse($data, $status)
	{
		$paylod = null;

		if (is_object($data) || is_array($data)) {
			$paylod = $data;
		} else if (is_string($data)) {
			$paylod = array('message' => $data);
		}
		return \Response::json($paylod, $status, array(), JSON_PRETTY_PRINT);
	}

}
