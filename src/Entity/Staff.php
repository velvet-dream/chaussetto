<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StaffRepository::class)]
class Staff implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre email doit faire moins de {{ limit }} caractères.',
    )]
    #[Assert\Email(
        message: '{{ value }} n\'est pas une adresse mail valide.',
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_ADMIN'];

    /**
     * @var string The hashed password
     */
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Votre mot de passe doit faire moins de {{ limit }} caractères.',
    )]
    // #[Assert\PasswordStrength([
    //     'minScore' => Assert\PasswordStrength::STRENGTH_WEAK, // Very strong password required
    //     'message' => "Votre mot de passe n'est pas assez sécure."
    // ])]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Votre nom doit faire moins de {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre prénom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Votre prénom doit faire moins de {{ limit }} caractères.',
    )]
    #[ORM\Column(length: 50)]
    private ?string $last_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }
}
