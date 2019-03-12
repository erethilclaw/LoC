<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

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
	 * @Route("/{page}", name="blog_list", defaults={"page" : 1}, requirements={"page"="\d+"})
	 */
	public function list($page, Request $request)
	{
		$limit = $request->get('limit',10);
		return $this->json(
			[
				'page'=> $page,
				'lmit'=> $limit,
				'data'=> array_map(function ($item){
					return $this->generateUrl('blog_by_slug', ['slug'=>$item['slug']]);
				},self::POSTS)
			]

		);
	}

	/**
	 * @Route("/add", name="blog_add", methods={"POST"})
	 */
	public function add(Request $request)
	{
		/**
		 * @var Serializer $serializer
		 */
		$serializer = $this->get('serializer');

		$blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

		$em = $this->getDoctrine()->getManager();
		$em->persist($blogPost);
		$em->flush($blogPost);

		return $this->json($blogPost);
	}

	/**
	 * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
	 */
	public function post($id)
	{
		return $this->json(
			self::POSTS [array_search($id, array_column(self::POSTS, 'id'))]
		);
	}

	/**
	 * @Route("/{slug}", name="blog_by_slug")
	 */
	public function postBySlug($slug)
	{
		return $this->json(
			self::POSTS [array_search($slug, array_column(self::POSTS, 'slug'))]
		);
	}
}