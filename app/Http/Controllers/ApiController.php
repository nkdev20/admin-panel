<?php

namespace App\Http\Controllers;

use App\Libraries\Fractal\NoDataArraySerializer;
use League\Fractal\Manager;
use Illuminate\Http\Response;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

class ApiController extends Controller
{
    private $manager;

    public function __construct(Manager $manager)
    {
		$this->manager = $manager;
    }

	function makeItem($data, $transformer, array $includes = [])
    {
        $this->manager->parseIncludes($includes);
        return $this->manager->createData(new Item($data, $transformer))->toArray();
    }

	function makeCollection($data, $transformer, array $includes = [])
	{
		$this->manager->parseIncludes($includes);
	
		return $this->manager->createData(new Collection($data, $transformer))->toArray();
	}

	function makePaginatedCollection($data, $transformer,$includes , $message = []) {

		return [
			'data' => $this->makeCollection($data->getCollection(), $transformer, $includes),
			'pagination' => [
				'total' => $data->total(),
				'count' => $data->count(),
				'per_page' => $data->perPage(),
				'current_page' => $data->currentPage(),
				'total_pages' => ceil($data->total() / $data->perPage()),
				'next_url' => $data->nextPageUrl()
			], 
			'message' => $message
		];
	}


	public function respondWithData($data)
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], Response::HTTP_OK);
	}



	public function respondNotFound($data, $status = Response::HTTP_NOT_FOUND)
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}



	public function responseBadRequest($data = [], $status = Response::HTTP_BAD_REQUEST) 
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}



	public function respondInternalServerError($data = [], $status = Response::HTTP_INTERNAL_SERVER_ERROR) 
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}


	public function responseValidationBadRequest($data = [], $status = Response::HTTP_BAD_REQUEST) {
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}



	public function respondUnauthorizedError($data = [], $status = Response::HTTP_UNAUTHORIZED)
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}




	public function respondEmailSendingFailed($data = [], $status = Response::HTTP_BAD_GATEWAY)
	{
		return response()->json([
			'data' =>  isset($data['data'])? $data['data'] : '',
			'pagination' => isset($data['pagination']) ? $data['pagination'] : '',
			'message' => isset($data['message']) ? $data['message']: ''
		], $status);
	}
}
