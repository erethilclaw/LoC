<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *          "order"={"created":"DESC"},
 *          "pagination_enabled" = false,
 *          "pagination_client_enabled" = true,
 *          "pagination_client_items_per_page" = true
 *     },
 *     itemOperations={
 *     "get",
 *     "put"={
 *              "access_control"= "is_granted('ROLE_EDITOR') or is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user"
 *          }
 *      },
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "access_control"= "is_granted('ROLE_COMMENTATOR')"
 *          },
 *          "api_blog_posts_comments_get_subresource"={
 *              "normalization_context"={
 *                  "groups"={"get-comment-with-author"}
 *              }
 *          }
 *      },
 *     denormalizationContext={
 *          "groups"={"post"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements AuthoredEntityInterface, CreatedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-comment-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Groups({"post","get-comment-with-author"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-comment-with-author"})
     */
    private $created;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 * @Groups({"get-comment-with-author"})
	 */
	private $author;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 * @Groups({"post"})
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

    public function setCreated(\DateTimeInterface $created): CreatedDateEntityInterface
    {
        $this->created = $created;

        return $this;
    }

	public function getAuthor(): ?User
	{
		return $this->author;
	}

	public function setAuthor(UserInterface $author ): AuthoredEntityInterface
	{
		$this->author = $author;

		return $this;
	}

	public function getBlogPost(): ?BlogPost
	{
		return $this->blogPost;
	}

	public function setBlogPost(BlogPost $blogPost ): self
	{
		$this->blogPost = $blogPost;

		return $this;
	}

	public function __toString(): string
	{
		return substr($this->content, 0,20)."...";
	}
}
