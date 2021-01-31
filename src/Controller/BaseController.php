<?php

namespace App\Controller;

use App\Exception\FormException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
	protected function processForm(string $formType, $entity, $options = []): void
	{
		$request = $this->get('request_stack')->getCurrentRequest();

		$form = $this->createForm($formType, $entity, $options);
		$data = json_decode($request->getContent(), true);

		$form->submit($data);

		if (!$form->isValid()) {
			throw new FormException($form);
		}
	}

	protected function get(string $serviceName)
	{
		return $this->container->get($serviceName);
	}

	protected function transactional(\Closure $func): void
	{
		$this->getDoctrine()->getManager()->transactional($func);
	}
}
