<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
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
 *      },
 *     denormalizationContext={
 *          "groups"={"post"}
 *     }
 * )
 */
class BlogPost implements AuthoredEntityInterface, CreatedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="5")
     * @Groups({"post"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @Groups({"post"})
     */
    private $published;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min="20")
     * @Groups({"post"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blogPost")
	 * @ApiSubresource()
	 */
	private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $slug;


	public function __construct()
	{
		$this->comments = new ArrayCollection();
	}

	public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

	public function getAuthor(): User
	{
		return $this->author;
	}

	public function setAuthor(UserInterface $author): AuthoredEntityInterface
	{
		$this->author = $author;

		return $this;
	}

	public function getComments(): Collection
	{
		return $this->comments;
	}
}
