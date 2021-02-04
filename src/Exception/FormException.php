<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormException extends HttpException
{
	protected $form;

	public function __construct(FormInterface $form)
	{
		parent::__construct(Response::HTTP_BAD_REQUEST);

		$this->form = $form;
	}

	public function getForm(): FormInterface
	{
		return $this->form;
	}

	public function getErrors()
	{
		return $this->form->getErrors(true);
	}
}
