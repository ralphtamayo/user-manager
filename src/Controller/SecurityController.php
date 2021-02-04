<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends BaseController
{
	/**
	 * @Route("/login", name="login", methods="POST")
	 */
	public function login(): Response
	{
		// This method can be blank - it will be intercepted by the logout key in firewall.
		throw new \LogicException();
	}

	/**
	 * @Route("/logout", name="logout", methods="POST")
	 */
	public function logout()
	{
		// This method can be blank - it will be intercepted by the logout key in firewall.
		throw new \LogicException();
	}
}
