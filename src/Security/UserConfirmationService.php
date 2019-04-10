<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	public function __construct(EntityManagerInterface $em,
								UserRepository $userRepository) {
		$this->em = $em;
		$this->userRepository = $userRepository;
	}

	public function confirmUser(string $confirmationToken)
	{
		$user = $this->userRepository->findOneBy([
			'confirmationToken'=> $confirmationToken
		]);

		if (!$user){
			throw new NotFoundHttpException();
		}

		$user->setEnabled(true);
		$user->setConfirmationToken(null);

		$this->em->flush();
	}
}