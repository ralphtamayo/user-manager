<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserAuthenticator extends AbstractGuardAuthenticator implements LogoutSuccessHandlerInterface
{
	const LOGIN_ROUTE = 'login';

	const ERROR_TYPE_INVALID_CREDENTIALS = 'invalid-credentials';
	const ERROR_TYPE_AUTHENTICATION_REQUIRED = 'authentication-required';

	private $passwordEncoder;
	private $serializer;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer)
	{
		$this->passwordEncoder = $passwordEncoder;
		$this->serializer = $serializer;
	}

	public function supports(Request $request)
	{
		return $request->attributes->get('_route') === self::LOGIN_ROUTE && $request->isMethod('POST');
	}

	public function getCredentials(Request $request)
	{
		$credentials = [
			'email' => $request->request->get('email'),
			'password' => $request->request->get('password'),
		];

		$request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);

		return [
			'email' => $credentials['email'],
			'password' => $credentials['password'],
		];
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		return $userProvider->loadUserByUsername($credentials['email']);
	}

	public function checkCredentials($credentials, UserInterface $user)
	{
		$isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
		if (!$isPasswordValid) {
			throw new BadCredentialsException();
		}

		return true;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		if ($exception instanceof BadCredentialsException || $exception instanceof UsernameNotFoundException) {
			return $this->createError(Response::HTTP_BAD_REQUEST, ['type' => self::ERROR_TYPE_INVALID_CREDENTIALS]);
		}

		return $this->createError(Response::HTTP_UNAUTHORIZED);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		return JsonResponse::fromJsonString($this->serializer->serialize($token->getUser(), 'json', [
			'groups' => 'login'
		]));
	}

	public function start(Request $request, AuthenticationException $authException = null)
	{
		return $this->createError(Response::HTTP_UNAUTHORIZED, ['type' => self::ERROR_TYPE_AUTHENTICATION_REQUIRED]);
	}

	public function supportsRememberMe()
	{
		return true;
	}

	public function onLogoutSuccess(Request $request): Response
	{
		return new Response();
	}

	private function createError($statusCode, $extras = [])
	{
		$response = ['error' => [
			'code' => $statusCode,
		] + $extras];

		return JsonResponse::fromJsonString($this->serializer->serialize($response, 'json'));
	}
}
