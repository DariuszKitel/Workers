<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *      "get"={
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *          "normalization_context"={
 *              "groups"={"get"}
 *              }
 *          },
 *     "put"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object === user",
 *              "denormalization_context"={
 *              "groups"={"put"}
 *              },
 *              "normalization_context"={
 *              "groups"={"get"}
 *              }
 *          }
 *     },
 *     collectionOperations={
 *     "post"={
 *     "denormalization_context"={
 *              "groups"={"post"}
 *              },
 *              "normalization_context"={
 *              "groups"={"get"}
 *              }
 *          }
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    const ROLE_QUESTIONER = 'ROLE_QUESTIONER';
    const ROLE_WRITER = 'ROLE_WRITER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    const DEFAULTS_ROLES = [self::ROLE_QUESTIONER];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "get-question-with-author", "get-work-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put", "post"})
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Hasło musi składać się conajmniej z 7 znaków i zawierać jedną cyfrę, jedną dużą literę oraz małą"
     * )
     */
    private $password;

    /**
     * @Groups({"put", "post"})
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Hasła nie pasują"
     * )
     */
    private $retypedPassword;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "put", "get-question-with-author", "get-work-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "put", "get-admin", "get-owner"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min="6", max="255", minMessage="Ten adres jest za krótki!", maxMessage="Przekroczono limt znaków!")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkPost", mappedBy="author")
     * @Groups({"get"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="author")
     * @Groups({"get"})
     */
    private $question;

    /**
     * @ORM\Column(type="simple_array", length=200)
     * @Groups({"get-admin", "get-owner"})
     */
    private $roles;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->question = new ArrayCollection();
        $this->roles = self::DEFAULTS_ROLES;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }


    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword($retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }

    public function addPost(WorkPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(WorkPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setAuthor($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getAuthor() === $this) {
                $question->setAuthor(null);
            }
        }

        return $this;
    }


}
