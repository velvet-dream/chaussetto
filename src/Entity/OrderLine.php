<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderLineRepository::class)]
class OrderLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $productName = null;

    #[ORM\Column]
    private ?float $productPrice = null;

    #[ORM\Column]
    private ?float $tax = null;

    #[ORM\Column(nullable: true)]
    private ?float $promotion = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $totalPriceVAT = null;

    #[ORM\ManyToOne(inversedBy: 'orderLine')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $correspondingOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): static
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getPromotion(): ?float
    {
        return $this->promotion;
    }

    public function setPromotion(?float $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPriceVAT(): ?float
    {
        return $this->totalPriceVAT;
    }

    public function setTotalPriceVAT(float $totalPriceVAT): static
    {
        $this->totalPriceVAT = $totalPriceVAT;

        return $this;
    }

    public function getCorrespondingOrder(): ?Order
    {
        return $this->correspondingOrder;
    }

    public function setCorrespondingOrder(?Order $correspondingOrder): static
    {
        $this->correspondingOrder = $correspondingOrder;

        return $this;
    }
}
