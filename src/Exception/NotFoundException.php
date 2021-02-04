<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotFoundException extends HttpException
{
	public function __construct()
	{
		parent::__construct(Response::HTTP_NOT_FOUND);
	}
}
