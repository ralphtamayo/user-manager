<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="Username has already been used.")
 * @UniqueEntity(fields={"email"}, message="Email has already been used.")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank(message="Email must not be blank.")
	 * @Assert\Email(message="Email is not valid.")
	 * @Assert\Length(max=180, maxMessage="Email has to be less than {{ limit }} characters.")
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=32, unique=true)
	 * @Assert\NotBlank(message="Username must not be blank.")
	 * @Assert\Length(max=32, maxMessage="Username has to be less than {{ limit }} characters.")
	 */
	private $username;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @Assert\NotBlank(message="Password must not be blank.", groups={"registration"})
	 * @Assert\Length(max=128, maxMessage="Password has to be less than {{ limit }} characters.")
	 */
	private $plaintextPassword;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank(message="First name must not be blank.")
	 * @Assert\Length(max=50, maxMessage="First name has to be less than {{ limit }} characters.")
	 */
	private $firstName;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 * @Assert\Length(max=50, maxMessage="Last name has to be less than {{ limit }} characters.")
	 */
	private $lastName;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isEnabled = true;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string) $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	public function getPlaintextPassword(): ?string
	{
		return $this->plaintextPassword;
	}

	public function setPlaintextPassword(?string $plaintextPassword): self
	{
		$this->plaintextPassword = $plaintextPassword;

		return $this;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(?string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(?string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function isEnabled(): ?bool
	{
		return $this->isEnabled;
	}

	public function setIsEnabled(string $isEnabled): self
	{
		$this->isEnabled = $isEnabled;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}
}
