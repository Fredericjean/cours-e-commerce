<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\EnableTrait;
use App\Repository\TaxeRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaxeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Taxe
{
    use EnableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 3,
        minMessage: "Le nom de la taxe ne peut pas faire moins de {{ limit }} caractères",
        max: 180,
        maxMessage :"Le nom de la taxe ne peut pas faire plus de {{ limit }} caractères"
    )]
    private ?string $name = null;


    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?float $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
