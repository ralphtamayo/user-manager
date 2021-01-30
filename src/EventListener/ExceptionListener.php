<?php

namespace App\EventListener;

use App\Exception\FormException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionListener
{
	private $serializer;

	public function __construct(SerializerInterface $serializer)
	{
		$this->serializer = $serializer;
	}

	public function onKernelException(ExceptionEvent $event)
	{
		$exception = $event->getException();

		if ($exception instanceof FormException) {
			$response = JsonResponse::fromJsonString($this->serializer->serialize([
				'error' => [
					'status' => $exception->getStatusCode(),
					'message' => 'invalid-form',
				],
				'form' => $this->buildFormErrors($exception),
			], 'json'));

			$event->setResponse($response);
		}
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
