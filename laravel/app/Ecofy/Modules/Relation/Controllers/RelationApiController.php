<?php
namespace App\Ecofy\Modules\Relation\Controllers;

use DB;
use Log;
use Illuminate\Http\Request;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Ecofy\Support\AbstractResourceController;
use App\Ecofy\Modules\Relation\RelationService;

class RelationApiController extends AbstractResourceController
{
	public function __construct() {
		parent::__construct(new RelationService);
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($containerUuid, Request $request)
	{
        $queryCtx = $this->queryContext($request);

		$criteria = $queryCtx->criteria;

		$resources = $this->service->queryRelationsOf($containerUuid, $criteria, $queryCtx);

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
		//var_dump($result);
		//die();

		return json_encode($result, JSON_PRETTY_PRINT);
	}

	/**
	 * Showing the form is not supported
	 *
	 */
	public function create($containerUuid)
	{
		return $this->jsonResponse('Unsupported endpoint', 404);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($containerUuid)
	{
		// Unsupported, must go through request first
		return $this->jsonResponse('Unsupported endpoint', 404);
	}

	/**
	 * Return JSON representation of the specified resource.
	 *
	 * @param  mixed  $id
	 * @return Response
	 */
	public function show($containerUuid, $id)
	{
		$resource = $this->service->findByPK($id);

		if (!empty($resource)) {
			return $this->jsonResponse(json_encode($resource, JSON_PRETTY_PRINT), 200);
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
	public function edit($containerUuid, $id)
	{
	    return $this->jsonResponse('Unsupported endpoint', 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  mixed  $id
	 * @return Response
	 */
	public function update($containerUuid, $id)
	{
		$data = \Input::all();

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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($containerUuid, $id)
	{
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
