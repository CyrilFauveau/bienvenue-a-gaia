<?php

namespace App\Entity;

use App\Repository\SkinCharacterBodyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinCharacterBodyRepository::class)
 */
class SkinCharacterBody extends Skin
{
    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Character::class, mappedBy="bodySkins")
     */
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
            $character->addBodySkin($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            $character->removeBodySkin($this);
        }

        return $this;
    }
}
