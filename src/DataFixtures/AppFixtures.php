<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * @var \Faker\Factory
	 */
	private $faker;

	public function __construct(UserPasswordEncoderInterface $password_encoder) {
		$this->passwordEncoder = $password_encoder;
		$this->faker = \Faker\Factory::create();
	}

	public function load(ObjectManager $manager)
    {
    	$this->loadUsers($manager);
		$this->loadBlogPosts($manager);
		$this->loadComments($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
	    $author = new User();
	    $author->setEmail('ernest.riccetto@gmail.com');
	    $author->setName('Ernest');
	    $author->setUsername('Sr Claw');
	    $author->setPassword(
	    	$this->passwordEncoder->encodePassword($author,'12345')
	    );

	    $this->addReference('admin_claw', $author);

	    $manager->persist($author);
	    $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
    	$author = $this->getReference('admin_claw');

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Cosmodaughter");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor($author);
	    $blogPost->setContent("flame deamon");
	    $blogPost->setSlug("she-darht-maul");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Sylvia");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor($author);
	    $blogPost->setContent("mermaid enchant");
	    $blogPost->setSlug("mermaid");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Marta");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor($author);
	    $blogPost->setContent("flames flames!!");
	    $blogPost->setSlug("marta-fire");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Nuur");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor($author);
	    $blogPost->setContent("tribal biker");
	    $blogPost->setSlug("tribal-biker");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Sofia");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor($author);
	    $blogPost->setContent("A witch and his ball");
	    $blogPost->setSlug("Sofia-gipsy");

	    $manager->persist($blogPost);

	    //loop with faker
	    for ($i=0; $i < 100; $i++)
	    {
		    $blogPost = new BlogPost();
		    $blogPost->setTitle($this->faker->realText(30));
		    $blogPost->setPublished($this->faker->dateTimeThisYear);
		    $blogPost->setCreated($blogPost->getPublished());
		    $blogPost->setAuthor($author);
		    $blogPost->setContent($this->faker->realText());
		    $blogPost->setSlug($this->faker->slug);

		    $this->setReference("blog_post".$i, $blogPost);

		    $manager->persist($blogPost);
	    }

	    $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
	    $author = $this->getReference('admin_claw');
		for ( $i=0; $i < 100; $i++)
		{
			for	( $j=0; $j < rand(1,10); $j++)
			{
				$comment = new Comment();
				$comment->setContent($this->faker->realText(50));
				$comment->setAuthor($author);
				$comment->setCreated($this->faker->dateTimeThisYear());

				$manager->persist($comment);
			}
		}
		$manager->flush();
    }
}
