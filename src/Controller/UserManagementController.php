<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\NotFoundException;
use App\Form\UserRegistrationType;
use App\Manager\UserManager;
use App\Service\ResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", methods="GET")
 */
class UserManagementController extends BaseController
{
	/**
	 * @Route("/", methods="GET")
	 */
	public function list(ResponseBuilder $responseBuilder): JsonResponse
	{
		$users = $this->getRepo(User::class)->findAll();

		return $responseBuilder->createJsonResponse($users, ['groups' => 'list']);
	}

	/**
	 * @Route("/{id}", methods="GET")
	 */
	public function details(int $id, ResponseBuilder $responseBuilder): JsonResponse
	{
		$user = $this->getRepo(User::class)->findOneById($id);

		if ($user === null) {
			throw new NotFoundException();
		}

		return $responseBuilder->createJsonResponse($user, ['groups' => 'details']);
	}
}
