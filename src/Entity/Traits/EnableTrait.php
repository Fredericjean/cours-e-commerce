<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EnableTrait
{
    #[ORM\Column]
    #[Assert\Type(
        type:'bool',
        message:'La valeur de enable doit être un booléan'
    )]
    private ?bool $enable = null;

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): static
    {
        $this->enable = $enable;

        return $this;
    }
}
