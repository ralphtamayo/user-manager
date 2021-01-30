<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
	/**
	 * @Route("/register", methods="POST")
	 */
	public function register(UserManager $userManager): JsonResponse
	{
		$user = new User();

		$this->processForm(UserRegistrationType::class, $user);

		$this->transactional(function ($em) use ($user, $userManager) {
			$userManager->save($user);
		});

		return JsonResponse::fromJsonString($this->getSerializer()->serialize($user, 'json'));
	}
}
