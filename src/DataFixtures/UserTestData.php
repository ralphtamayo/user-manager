<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class UserTestData extends Fixture
{
	const USER_USERNAME = 'admin';
	const USER_EMAIL = 'admin@sample.com';
	const USER_PASSWORD = 'password123';

	private $kernel;
	private $userManager;

	public function __construct(KernelInterface $kernel, UserManager $userManager)
	{
		$this->kernel = $kernel;
		$this->userManager = $userManager;
	}

	public function load(ObjectManager $manager)
	{
		if ($this->kernel->getEnvironment() !== 'test') {
			return;
		}

		$user = new User();
		$user->setUsername(self::USER_USERNAME)
			->setEmail(self::USER_EMAIL)
			->setPlaintextPassword(self::USER_PASSWORD)
			->setFirstName('Administrator')
		;

		$this->userManager->save($user);

		$manager->flush();
	}
}
