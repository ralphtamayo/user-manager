<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class UserTestData extends Fixture
{
	const USER_EMAIL = 'ralphtamayo@sample.com';
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
		$user->setEmail(self::USER_EMAIL);
		$user->setPlaintextPassword(self::USER_PASSWORD);
		$user->setFirstName('Administrator');

		$this->userManager->save($user);

		$manager->flush();
	}
}
