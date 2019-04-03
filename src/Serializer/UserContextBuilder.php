<?php

namespace App\Serializer;

use ApiPlatform\Core\Exception\RuntimeException;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserContextBuilder implements SerializerContextBuilderInterface
{
	/**
	 * @var SerializerContextBuilderInterface
	 */
	private $decorated;
	/**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorization_checker;

	public function __construct(SerializerContextBuilderInterface $decorated,
	AuthorizationCheckerInterface $authorization_checker) {
		$this->decorated = $decorated;
		$this->authorization_checker = $authorization_checker;
	}

	public function createFromRequest( Request $request,
		bool $normalization,
		array $extractedAttributes = null
	): array {
		$context = $this->decorated->createFromRequest(
			$request, $normalization, $extractedAttributes
		);

		$resourceClass = $context['resource_class'] ?? null;

		if (
			User::class === $resourceClass &&
			isset($context['groups']) &&
			$normalization === true &&
			$this->authorization_checker->isGranted(User::ROLE_ADMIN)
		) {
			$context['groups'][] = 'get-admin';
		}

		return $context;
	}


}