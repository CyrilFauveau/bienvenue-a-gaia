<?php

namespace App\Entity;

use App\Repository\SkinCharacterHeadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinCharacterHeadRepository::class)
 */
class SkinCharacterHead extends Skin
{
    /**
     * @ORM\Column(type="text")
     */
    private $imageAccepted;

    /**
     * @ORM\Column(type="text")
     */
    private $imageNeutral;

    /**
     * @ORM\Column(type="text")
     */
    private $imageDenied;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, mappedBy="headSkins")
     */
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getImageAccepted(): ?string
    {
        return $this->imageAccepted;
    }

    public function setImageAccepted(string $imageAccepted): self
    {
        $this->imageAccepted = $imageAccepted;

        return $this;
    }

    public function getImageNeutral(): ?string
    {
        return $this->imageNeutral;
    }

    public function setImageNeutral(string $imageNeutral): self
    {
        $this->imageNeutral = $imageNeutral;

        return $this;
    }

    public function getImageDenied(): ?string
    {
        return $this->imageDenied;
    }

    public function setImageDenied(string $imageDenied): self
    {
        $this->imageDenied = $imageDenied;

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->addHeadSkin($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            $character->removeHeadSkin($this);
        }

        return $this;
    }
}
