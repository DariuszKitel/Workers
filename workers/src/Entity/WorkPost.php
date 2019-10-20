<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Interfaces\AuthoredEntityInterface;
use App\Interfaces\PublishedDateEntityInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkPostRepository")
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "title": "partial",
 *          "content": "partial",
 *          "author": "exact",
 *          "author.name": "partial"
 *     }
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={
 *          "published"
 *     }
 * )
 * @ApiFilter(RangeFilter::class, properties={"id"})
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *          "id",
 *          "published",
 *          "title"
 *     },
 *     arguments={"orderParameterName"="_order"}
 * )
 * @ApiFilter(PropertyFilter::class, arguments={
 *     "parameterName": "properties",
 *     "overrideDefaultProperties": false,
 *     "whitelist": {"id", "author", "slug", "title", "content"}
 *     })
 * @ApiResource(
 *     attributes={"order"={"published": "DESC"}, "maximum_items_per_page"=30, "pagination_partial"=true},
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"get-work-post-with-author"}
 *              }
 *          },
 *          "put"={
 *              "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user)"
 *          }
 *      },
 *     collectionOperations={
 *     "get",
 *     "post"={
 *          "access_control"="is_granted('ROLE_WRITER')"
 *          }
 *      },
 *      denormalizationContext={
            "groups"={"post"}
 *     }
 *   )
 */
class WorkPost implements AuthoredEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-work-post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, minMessage="Wpis musi być dłuższy niż 5 znaków")
     * @Groups({"post", "get-work-post-with-author"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-work-post-with-author"})
     */
    private $published;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=20, minMessage="Zawartość musi być dłuższa niż 20 znaków")
     * @Groups({"post", "get-work-post-with-author"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-work-post-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CV")
     * @ORM\JoinTable()
     * @ApiSubresource()
     * @Groups({"post", "get-work-post-with-author"})
     */
    private $CV;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Groups({"post", "get-work-post-with-author"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="workPost")
     * @ApiSubresource()
     * @Groups({"get-work-post-with-author"})
     */
    private $question;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image")
     * @ORM\JoinTable()
     * @ApiSubresource()
     * @Groups({"post", "get-work-post-with-author"})
     */
    private $images;

    public function __construct()
    {
        $this->question = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->CV = new ArrayCollection();
    }

    public function getQuestion(): Collection
    {
        return $this->question;
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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
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

    public function getCV(): Collection
    {
        return $this->CV;
    }

    public function addCV(CV $cv)
    {
        $this->CV->add($cv);
    }

    public function removeCV(CV $cv)
    {
        $this->CV->removeElement($cv);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param UserInterface $author
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }


    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images->add($image);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    public function __toString(): string
    {
        return $this->title;
    }


}
