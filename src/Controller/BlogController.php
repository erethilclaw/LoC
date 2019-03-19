<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

/**
 * Class BlogController
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
	/**
	 * @Route("/{page}", name="blog_list", defaults={"page" : 1}, requirements={"page"="\d+"})
	 */
	public function list($page, Request $request)
	{
		$limit = $request->get('limit',10);
		$blogPostRepository = $this->getDoctrine()->getRepository(BlogPost::class);
		$blogPostList = $blogPostRepository->findAll();
		return $this->json(
			[
				'page'=> $page,
				'lmit'=> $limit,
				'data'=> array_map(function (BlogPost $blogPost){
					return $this->generateUrl('blog_by_slug', ['slug'=>$blogPost->getSlug()]);
				}, $blogPostList)
			]

		);
	}

	/**
	 * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
	 * @ParamConverter("post", class="App:BlogPost")
	 */
	public function post($post)
	{
		return $this->json(
			$post
		);
	}

	/**
	 * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
	 * the below annotation is just an example for extend the one above
	 * @ParamConverter("post", class="App:BlogPost", options={"mapping":{"slug":"slug"}})
	 */
	public function postBySlug(BlogPost $post)
	{
		return $this->json(
			$post
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
	 * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
	 */
	public function delete(BlogPost $post)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($post);
		$em->flush();

		return new JsonResponse( null, Response::HTTP_NO_CONTENT);
	}
}