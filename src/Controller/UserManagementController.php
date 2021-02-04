<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\NotFoundException;
use App\Form\UserRegistrationType;
use App\Form\UserType;
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
	 * @Route("", methods="GET")
	 */
	public function list(ResponseBuilder $responseBuilder): JsonResponse
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$users = $this->getRepo(User::class)->findAll();

		return $responseBuilder->createJsonResponse($users, ['groups' => 'list']);
	}

	/**
	 * @Route("", methods="POST")
	 */
	public function create(UserManager $userManager, ResponseBuilder $responseBuilder): JsonResponse
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = new User();

		$this->processForm(UserRegistrationType::class, $user);

		$this->transactional(function ($em) use ($user, $userManager) {
			$userManager->save($user);
		});

		return $responseBuilder->createJsonResponse($user);
	}

	/**
	 * @Route("/{id}", methods="GET")
	 */
	public function details(int $id, ResponseBuilder $responseBuilder): JsonResponse
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$user = $this->getRepo(User::class)->findOneById($id);

		if ($user === null) {
			throw new NotFoundException();
		}

		return $responseBuilder->createJsonResponse($user, ['groups' => 'details']);
	}

	/**
	 * @Route("/{id}", methods="PUT")
	 */
	public function update(int $id, UserManager $userManager, ResponseBuilder $responseBuilder): JsonResponse
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		$user = $this->getRepo(User::class)->findOneById($id);

		if ($user === null) {
			throw new NotFoundException();
		}

		$this->processForm(UserType::class, $user);

		$this->transactional(function ($em) use ($user, $userManager) {
			$userManager->save($user);
		});

		return $responseBuilder->createJsonResponse($user, ['groups' => 'details']);
	}
}
