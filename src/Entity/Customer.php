<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
    private ?string $lastName = null;

    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre email doit faire moins de {{ limit }} caractères.',
    )]
    #[Assert\Email(
        message: '{{ value }} n\'est pas une adresse mail valide.',
    )]
    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Votre mot de passe doit faire moins de {{ limit }} caractères.',
    )]
    #[Assert\PasswordStrength([
        'minScore' => Assert\PasswordStrength::STRENGTH_WEAK, // Very strong password required
        'message' => "Votre mot de passe n'est pas assez sécure."
    ])]
    #[ORM\Column(length: 100)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];


    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Adress $adress = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Cart::class)]
    private Collection $carts;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
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
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setCustomer($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getCustomer() === $this) {
                $cart->setCustomer(null);
            }
        }

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
     * Get the value of roles
     */ 
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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

}
