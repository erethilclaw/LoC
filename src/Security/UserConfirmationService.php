<?php

namespace App\Security;

use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
			throw new InvalidConfirmationTokenException();
		}

		$user->setEnabled(true);
		$user->setConfirmationToken(null);

		$this->em->flush();
	}
}