<?php
namespace App\Ecofy\Support;

//use DB;
use Log;
use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Ecofy\Support\SRQLParser;

/**
 * A ntested resource allows an arbitrary number of nesting of resource in the
 * URL path. For example users/{userId}/blogs/{blogId}/comments/{commentId}
 */
abstract class AbstractNestedResourceApiController extends AbstractResourceController
{
	/**
	 * @type {array}
	 */
	private $containerFieldNames;

	/**
	 * The $containerFieldNames provides the mapping of the parameters by index
	 * in the URL to be mapped to the resuurce model's property.
	 * For example given $containerFieldNames = ['userId', 'blogId', 'commentId']
	 * and the URL:
	 * users/{id1}/blogs/{id2}/comments/{id3}
	 * The value of id1 will be assigned to the property userId, value of id2
	 * to blogId and value of id3 to commentId
	 *
	 * @param Service $service - the service that handles this Resource
	 * @param array $containerFieldNames - the array of container's field names
	 */
	public function __construct($service, $containerFieldNames) {
		parent::__construct($service);
		$this->containerFieldNames = $containerFieldNames;
	}

	/**
	 * Builds a map of <fieldName> => <argumentValue>
	 * @return {array}
	 */
	protected function buildFieldFromArgs($args)
	{
		$retval = [];

		$i = 0;
		foreach($args as $arg) {
			if ($arg instanceof Request)
				continue;
			$fieldName = $this->containerFieldNames[$i];
			$retval[$fieldName] = $arg;
			$i++;
		}

		return $retval;
	}

    /**
	 * Handles GET method without the resource id
	 * Returns a list of the resource.
	 *
	 * @param {Requeest} $request
	 * @return Response
	 */
	//public function index(Request $request)
	public function index(Request $request)
	{
		$keyParams = $this->buildFieldFromArgs(func_get_args());

        $queryCtx = $this->queryContext($request);

		$resources = $this->service->query($queryCtx->criteria, $queryCtx);

		$result = null;
		if ($queryCtx->envelop) {
			$result = [
                'criteria' => $queryCtx->q,
                'page' => $queryCtx->page,
                'offset' => $queryCtx->offset,
                'limit' => $queryCtx->limit
			];
			$result['documents'] = $resources;
			$result['totalHits'] = $this->service->count($queryCtx->criteria);
		} else {
			$result = $resources;
		}

		return $this->jsonResponse($result, 200);
	}

	/**
	 * Showing the form is not supported
	 *
	 */
	public function create()
	{
		return $this->jsonResponse('Unsupported endpoint', 404);
	}

	/**
	 * Handles POST method
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$keyParams = $this->buildFieldFromArgs(func_get_args());
		$data = array_merge($keyParams, $request->all());

		$createMethod = 'add';

        try {
        	$this->beforeResourceCreate($data);
            $resource = $this->service->$createMethod($data);
            $this->afterResourceCreate($resource);

			return $this->jsonResponse(array('added' => $resource->uuid), 201);
        } catch (Exception $e) {
			// @todo set the status code accordingly
            return $this->jsonResponse(array('error' => $e->getMessage()), 500);
        }
	}

	/**
 	 * Handles GET method with resource id
	 * Return JSON representation of the specified resource.
	 *
	 * @param  mixed  $id
	 * @return Response
	 */
	public function show(Request $request)
	{
		$keyParams = $this->buildFieldFromArgs(func_get_args());
		$id = end($keyParams);

		$options = $request->all();

		// @todo - query by the keyParams
		$resource = $this->service->findByPK($id, $options);

		if (!empty($resource)) {
			return $this->jsonResponse($resource, 200);
		} else {
			return $this->jsonResponse('Record not found: ' . $id, 404);
		}
	}

	/**
	 * Showing the form is not supported in API.
	 *
	 * @param  mixed  $id
	 * @return Response
	 */
	public function edit()
	{
	    return $this->jsonResponse('Unsupported endpoint', 404);
	}

	/**
	 * Handles PUT method
	 * Update the specified resource in storage.
	 *
	 * @param  mixed  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$keyParams = $this->buildFieldFromArgs(func_get_args());
		$id = end($keyParams);

		$data = $request->all();

        $updateMethod = 'update';

        try {
			$this->beforeResourceUpdate($data);
            $resource = $this->service->$updateMethod($id, $data);
            $this->afterResourceUpdate($resource);

			return $this->jsonResponse(array('updated' => $id), 200);

        } catch (Exception $e) {
			return $this->jsonResponse(array('error' => $e->getMessage()), 500);
        }
	}

	/**
	 * Handles DELETE method
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		$keyParams = $this->buildFieldFromArgs(func_get_args());
		$id = end($keyParams);

		$deleteMethod = 'removeByPK';
		$result = $this->service->$deleteMethod($id);

		if (!empty($result)) {
			\Log::debug('Removed ' . $this->service->getModelFqn() . ': ' . $id);
		} else {
			\Log::info('Record ' . $id . ' not found');
		}

		if (!empty($result)) {
			return $this->jsonResponse(array('removed' => $id), 200);
		} else {
			return $this->jsonResponse('Record not found: ' . $id, 404);
		}
	}


}
