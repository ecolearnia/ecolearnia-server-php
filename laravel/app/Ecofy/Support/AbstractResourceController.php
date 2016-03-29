<?php
namespace App\Ecofy\Support;

use DB;
use Log;
use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Ecofy\Support\SRQLParser;

abstract class AbstractResourceController extends BaseController
{
	protected $service = null;

	public function __construct($service) {
		$this->service = $service;
	}

	/**
	 * Builds QueryContext object from request.
	 * Creates $criteria property which is the EcoCriteria representation of $q
	 */
	public function queryContext($request)
    {
        $queryCtx = new \stdClass();
        $queryCtx->q = $request->input('q', false);
        $queryCtx->envelop = $request->input('_meta', false);
        $queryCtx->limit = $request->input('_limit');
        $queryCtx->offset = $request->input('_offset');
        $queryCtx->page = $request->input('_page');
        $queryCtx->criteria = null;

        if (!empty($queryCtx->q)) {
			// Convert the query (SRQL) into EcoCriteria
            $srqlParser = new SRQLParser();
            $qast = $srqlParser->parse($queryCtx->q);
            $queryCtx->criteria = $qast->text;
        }

        return $queryCtx;
    }

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

	/**
	 * Method that is called before creating
	 * @param array $data  - the input data from which the resource will be created
	 */
	protected function beforeResourceCreate(&$data) {
		// Default do nothing
	}

	/**
	 * Method that is called after resource is created
	 * @param object $resource - the resource that was created
	 */
	protected function afterResourceCreate(&$resource) {
		// Default do nothing
	}

	/**
	 * Method that is called before creating
	 * @param array $data - the input data from which the record will be updated
	 */
	protected function beforeResourceUpdate(&$data) {
		// Default do nothing
	}

	/**
	 * Method that is called after $resource was updated
	 * @param object $resource - the $resource that was updated
	 */
	protected function afterResourceUpdate(&$resource) {
		// Default do nothing
	}

}
