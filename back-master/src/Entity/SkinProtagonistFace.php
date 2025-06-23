<?php

namespace App\Entity;

use App\Repository\SkinProtagonistFaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinProtagonistFaceRepository::class)
 */
class SkinProtagonistFace extends Skin
{
    use SkinPurchasable;

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
