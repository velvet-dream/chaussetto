<?php

namespace App\Entity;

use App\Repository\NewsletterSubscribersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('adress', message:'Cette adresse est déjà enregistrée')]
#[ORM\Entity(repositoryClass: NewsletterSubscribersRepository::class)]
class NewsletterSubscribers
{
    
    #[Assert\Email(
        message: '{{ value }} n\'est pas une adresse valide.',
    )]
    #[ORM\Id]
    #[ORM\Column(length: 100)]
    private ?string $adress = null;

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }
}
