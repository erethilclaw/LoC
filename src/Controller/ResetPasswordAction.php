<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction {

	/**
	 * @var ValidatorInterface
	 */
	private $validator;
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $password_encoder;
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;
	/**
	 * @var JWTTokenManagerInterface
	 */
	private $JWT_token_manager;

	public function __construct(
		ValidatorInterface $validator,
		UserPasswordEncoderInterface $password_encoder,
		EntityManagerInterface $manager,
		JWTTokenManagerInterface $JWT_token_manager
	)
	{

		$this->validator = $validator;
		$this->password_encoder = $password_encoder;
		$this->manager = $manager;
		$this->JWT_token_manager = $JWT_token_manager;
	}

	public function __invoke(User $data)
	{
		$this->validator->validate($data);

		$data->setPassword(
			$this->password_encoder->encodePassword(
				$data, $data->getNewPassword()
			)
		);

		$data->setPasswordChangeDate(time());

		$this->manager->flush();

		$token = $this->JWT_token_manager->create($data);

		return new JsonResponse(['token'=> $token]);
	}
}