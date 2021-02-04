<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
	private $_em;
	private $passwordEncoder;

	public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
	{
		$this->_em = $entityManager;
		$this->passwordEncoder = $userPasswordEncoder;
	}

	public function save(User $user)
	{
		$user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlaintextPassword()));

		$this->_em->persist($user);
	}
}
