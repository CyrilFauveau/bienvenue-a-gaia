<?php

namespace App\Entity;

use App\Repository\SkinBackgroundRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinBackgroundRepository::class)
 */
class SkinBackground extends Skin
{
    /**
     * @ORM\Column(type="text")
     */
    private $image;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
