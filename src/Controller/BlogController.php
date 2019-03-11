<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BlogController
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
	private const POSTS = [
		[
			'id' => 1,
			'title' => 'Nur',
			'slug' => 'motera'
		],
		[
			'id' => 2,
			'title' => 'Sylvia',
			'slug' => 'sirena'
		],
		[
			'id' => 3,
			'title' => 'Anna',
			'slug' => 'moonchild'
		],
		[
			'id' => 4,
			'title' => 'Marta',
			'slug' => 'flames'
		],
		[
			'id' => 5,
			'title' => 'Sofia',
			'slug' => 'gipsy'
		],
	];

	/**
	 * @Route("/", name="blog_list")
	 */
	public function list()
	{
		return new JsonResponse(self::POSTS);
	}

	/**
	 * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
	 */
	public function post($id)
	{
		return new JsonResponse(
			self::POSTS [array_search($id, array_column(self::POSTS, 'id'))]
		);
	}

	/**
	 * @Route("/{slug}", name="blog_by_slug")
	 */
	public function postBySlug($slug)
	{
		return new JsonResponse(
			self::POSTS [array_search($slug, array_column(self::POSTS, 'slug'))]
		);
	}
}