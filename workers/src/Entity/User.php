<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\ResetPassAction;

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
 *          },
 *     "put-reset-pass"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object === user",
 *              "method"="PUT",
 *              "path"="/users/{id}/reset-pass",
 *              "controller"=ResetPassAction::class,
 *              "denormalization_context"={
 *              "groups"={"put-reset-pass"}
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
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!", groups={"post"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Hasło musi składać się conajmniej z 7 znaków i zawierać jedną cyfrę, jedną dużą literę oraz małą",
     *     groups={"post"}
     * )
     */
    private $password;

    /**
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Hasła nie pasują",
     *     groups={"post"}
     * )
     */
    private $retypedPassword;

    /**
     * @Groups({"put-reset-pass"})
     * @Assert\NotBlank(groups={"put-reset-pass"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Hasło musi składać się conajmniej z 7 znaków i zawierać jedną cyfrę, jedną dużą literę oraz małą",
     *     groups={"put-reset-pass"}
     * )
     */
    private $newPass;

    /**
     * @Groups({"put-reset-pass"})
     * @Assert\NotBlank(groups={"put-reset-pass"})
     * @Assert\Expression(
     *     "this.getNewPass() === this.getNewRetypedPass()",
     *     message="Hasła nie pasują",
     *     groups={"put-reset-pass"}
     * )
     */
    private $newRetypedPass;

    /**
     * @Groups({"put-reset-pass"})
     * @Assert\NotBlank(groups={"put-reset-pass"})
     * @UserPassword(groups={"put-reset-pass"})
     */
    private $oldPass;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "put", "get-question-with-author", "get-work-post-with-author"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!", groups={"post", "put"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "put", "get-admin", "get-owner"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Email(groups={"post", "put"})
     * @Assert\Length(min="6", max="255", minMessage="Ten adres jest za krótki!", maxMessage="Przekroczono limt znaków!", groups={"post", "put"})
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
     * @ORM\Column(type="simple_array", length=200, options={"default":"ROLE_QUESTIONER"})
     * @Groups({"get-admin", "get-owner"})
     */
    private $roles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passChangeDate;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $confirmationToken;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->question = new ArrayCollection();
        $this->roles = self::DEFAULTS_ROLES;
        $this->enabled = false;
        $this->confirmationToken = null;
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


    public function getNewPass(): ?string
    {
        return $this->newPass;
    }


    public function setNewPass($newPass): void
    {
        $this->newPass = $newPass;
    }


    public function getNewRetypedPass(): ?string
    {
        return $this->newRetypedPass;
    }


    public function setNewRetypedPass($newRetypedPass): void
    {
        $this->newRetypedPass = $newRetypedPass;
    }


    public function getOldPass(): ?string
    {
        return $this->oldPass;
    }


    public function setOldPass($oldPass): void
    {
        $this->oldPass = $oldPass;
    }


    public function getPassChangeDate()
    {
        return $this->passChangeDate;
    }


    public function setPassChangeDate($passChangeDate): void
    {
        $this->passChangeDate = $passChangeDate;
    }


    public function getEnabled()
    {
        return $this->enabled;
    }


    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }


    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }


    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
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
