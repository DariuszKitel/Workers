<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Interfaces\AuthoredEntityInterface;
use App\Interfaces\PublishedDateEntityInterface;

/**
 * @ApiResource(
 *     attributes={
 *     "order"={"published": "DESC"},
 *     "pagination_client_enabled"=true,
 *     "pagination_client_items_per_page"=true
 *      },
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_QUESTIONER') and object.getAuthor() == user)"
 *          }
 *      },
 *      *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_QUESTIONER')",
 *             "normalization_context"={
 *                 "groups"={"get-question-with-author"}
 *             }
 *         }
 *     },
 *     subresourceOperations={
 *         "api_work_posts_questions_get_subresource"={
 *             "normalization_context"={
 *                 "groups"={"get-question-with-author"}
 *             }
 *         }
 *     },
 *      denormalizationContext={
 *          "groups"={"post"}
 *     }
 *   )
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question implements AuthoredEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-question-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post", "get-question-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min="5", minMessage="Pytanie musi zawierać minimum 5 znaków!", max="3000", maxMessage="Przekroczono maksymalny 3000 limit znaków")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-question-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="question")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-question-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WorkPost", inversedBy="question")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     */
    private $workPost;

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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
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

    public function getWorkPost(): WorkPost
    {
        return $this->workPost;
    }

    public function setWorkPost(WorkPost $workPost): self
    {
        $this->workPost = $workPost;

        return $this;
    }



}
