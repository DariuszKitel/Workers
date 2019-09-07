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
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"
 *          }
 *     },
 *     collectionOperations={"post"},
 *     normalizationContext={
 *      "groups"={"read"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Hasło musi składać się conajmniej z 7 znaków i zawierać jedną cyfrę, jedną dużą literę oraz małą"
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Hasła nie pasują"
     * )
     */
    private $retypedPassword;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2", minMessage="Imię musi posiadać conajmniej 2 znaki", max="255", maxMessage="Przekroczono limit znaków!")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min="6", max="255", minMessage="Ten adres jest za krótki!", maxMessage="Przekroczono limt znaków!")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkPost", mappedBy="author")
     * @Groups({"read"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="author")
     * @Groups({"read"})
     */
    private $question;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->question = new ArrayCollection();
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


    public function getRoles()
    {
        return ['ROLE_USER'];
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


}
