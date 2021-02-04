<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Manager\UserManager;
use App\Service\ResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
	/**
	 * @Route("/profile", methods="GET")
	 */
	public function profile(ResponseBuilder $responseBuilder): JsonResponse
	{
		return $responseBuilder->createJsonResponse($this->getUser(), ['groups' => 'profile']);
	}

	/**
	 * @Route("/register", methods="POST")
	 */
	public function register(UserManager $userManager, ResponseBuilder $responseBuilder): JsonResponse
	{
		$user = new User();

		$this->processForm(UserRegistrationType::class, $user);

		$this->transactional(function ($em) use ($user, $userManager) {
			$userManager->save($user);
		});

		return $responseBuilder->createJsonResponse($user);
	}
}
