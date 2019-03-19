<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle("Cosmodaughter");
        $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
        $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
		$blogPost->setAuthor("Mr Claw");
		$blogPost->setContent("flame deamon");
		$blogPost->setSlug("she-darht-maul");

		$manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Sylvia");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor("Mr Claw");
	    $blogPost->setContent("mermaid enchant");
	    $blogPost->setSlug("mermaid");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Marta");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor("Mr Claw");
	    $blogPost->setContent("flames flames!!");
	    $blogPost->setSlug("marta-fire");

	    $manager->persist($blogPost);

	    $blogPost = new BlogPost();
	    $blogPost->setTitle("Nuur");
	    $blogPost->setPublished(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setCreated(new \DateTime("2018-07-01 12:00:00"));
	    $blogPost->setAuthor("Mr Claw");
	    $blogPost->setContent("tribal biker");
	    $blogPost->setSlug("tribal-biker");

	    $manager->persist($blogPost);

        $manager->flush();
    }
}
