<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     itemOperations={
 *     "get",
 *     "put"={
 *              "access_control"= "is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user"
 *          }
 *      },
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"= "is_granted('IS_AUTHENTICATED_FULLY')"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $author;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $blogPost;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

	public function getAuthor(): User
	{
		return $this->author;
	}

	public function setAuthor(User $author ): self
	{
		$this->author = $author;

		return $this;
	}

	public function getBlogPost(): BlogPost
	{
		return $this->blogPost;
	}

	public function setBlogPost(BlogPost $blogPost ): self
	{
		$this->blogPost = $blogPost;

		return $this;
	}


}