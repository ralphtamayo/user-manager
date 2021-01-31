<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseBuilder
{
	private $serializer;

	public function __construct(SerializerInterface $serializer)
	{
		$this->serializer = $serializer;
	}

	public function createJsonResponse($data, $groups = [], $format = 'json')
	{
		return JsonResponse::fromJsonString($this->serializer->serialize($data, $format, $groups));
	}
}
