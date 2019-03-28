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

	private const USERS = [
		[
			'username'=>'admin',
			'name'=>'Claw',
			'mail'=>'ernest.riccetto@gmail.com',
			'password'=>'12345'
		],
		[
			'username'=>'sylvia',
			'name'=>'Sylvia',
			'mail'=>'mermaid@gmail.com',
			'password'=>'12345'
		],
		[
			'username'=>'marta_flames',
			'name'=>'MartaFlames',
			'mail'=>'MartaFlameso@gmail.com',
			'password'=>'12345'
		],
		[
			'username'=>'cosmodaughter',
			'name'=>'Cosmodaughter',
			'mail'=>'Cosmodaughter@gmail.com',
			'password'=>'12345'
		],
		[
			'username'=>'gipsy_sereia',
			'name'=>'Sofia',
			'mail'=>'gipsy_sereia@gmail.com',
			'password'=>'12345'
		],
		[
			'username'=>'tribal_biker',
			'name'=>'Nuur',
			'mail'=>'Nuur@gmail.com',
			'password'=>'12345'
		]
	];

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
    	foreach (self::USERS as $user){
		    $author = new User();
		    $author->setEmail($user['mail']);
		    $author->setName($user['name']);
		    $author->setUsername($user['username']);
		    $author->setPassword(
			    $this->passwordEncoder->encodePassword($author,$user['password'])
		    );

		    $this->addReference('user_'.$user['name'], $author);

		    $manager->persist($author);
	    }

	    $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
	    //loop with faker
	    for ($i=0; $i < 100; $i++)
	    {
		    $blogPost = new BlogPost();
		    $blogPost->setTitle($this->faker->realText(30));
		    $blogPost->setPublished($this->faker->dateTimeThisYear);
		    $blogPost->setCreated($blogPost->getPublished());

		    $author = $this->getRandomUser();

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
	    for ( $i=0; $i < 100; $i++)
		{
			for	( $j=0; $j < rand(1,10); $j++)
			{
				$comment = new Comment();
				$comment->setContent($this->faker->realText(50));

				$author = $this->getRandomUser();

				$comment->setAuthor($author);
				$comment->setCreated($this->faker->dateTimeThisYear());
				$comment->setBlogPost($this->getReference("blog_post".$i));

				$manager->persist($comment);
			}
		}
		$manager->flush();
    }

	/**
	 * @return string
	 */
	protected function getRandomUser(): User {
		return $this->getReference('user_' . self::USERS[ ( rand( 0, 5 ) ) ]['name']);
	}
}
