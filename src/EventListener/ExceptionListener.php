<?php

namespace App\EventListener;

use App\Exception\FormException;
use App\Exception\NotFoundException;
use App\Service\ResponseBuilder;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
	private $responseBuilder;

	public function __construct(ResponseBuilder $responseBuilder)
	{
		$this->responseBuilder = $responseBuilder;
	}

	public function onKernelException(ExceptionEvent $event)
	{
		$exception = $event->getException();
		$error = ['error' => [
			'status' => $exception->getStatusCode(),
		]];

		if ($exception instanceof FormException) {
			$error['error']['message'] = 'invalid-form';
			$error['form'] = $this->buildFormErrors($exception);
		} elseif ($exception instanceof NotFoundException) {
			$error['error']['message'] = 'not-found';
		}

		$response = $this->responseBuilder->createJsonResponse($error);
		$event->setResponse($response);
	}

	private function buildFormErrors($exception)
	{
		$data = [];
		$errors = $exception->getErrors();

		foreach ($errors as $error) {
			$data[$error->getOrigin()->getName()][] = $error->getMessage();
		}

		return $data;
	}
}
