<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

	public function loadUserByUsername($usernameOrEmail)
	{
		return $this->createQueryBuilder('user')
			->select('user')
			->where('user.email = :usernameOrEmail')
			->orWhere('user.username = :usernameOrEmail')
			->setParameter('usernameOrEmail', $usernameOrEmail)
			->getQuery()
			->getOneOrNullResult()
		;
	}
}
