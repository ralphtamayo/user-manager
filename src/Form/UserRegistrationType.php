<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('email', Type\TextType::class)
			->add('username', Type\TextType::class)
			->add('firstName', Type\TextType::class)
			->add('lastName', Type\TextType::class)
			->add('plaintextPassword', Type\RepeatedType::class, [
				'type' => Type\PasswordType::class,
				'invalid_message' => 'The passwords do not match.',
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(['data_class' => User::class]);
	}
}
