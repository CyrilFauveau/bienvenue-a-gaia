<?php

namespace App\Entity;

use App\Repository\SkinsProtagonistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinsProtagonistRepository::class)
 */
class SkinsProtagonist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=SkinProtagonistBody::class)
     */
    private $bodies;

    /**
     * @ORM\ManyToMany(targetEntity=SkinProtagonistFace::class)
     */
    private $faces;

    /**
     * @ORM\ManyToMany(targetEntity=SkinProtagonistAccessory::class)
     */
    private $accessories;

    public function __construct()
    {
        $this->bodies = new ArrayCollection();
        $this->faces = new ArrayCollection();
        $this->accessories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|SkinProtagonistBody[]
     */
    public function getBodies(): Collection
    {
        return $this->bodies;
    }

    public function addBody(SkinProtagonistBody $body): self
    {
        if (!$this->bodies->contains($body)) {
            $this->bodies[] = $body;
        }

        return $this;
    }

    public function removeBody(SkinProtagonistBody $body): self
    {
        $this->bodies->removeElement($body);

        return $this;
    }

    /**
     * @return Collection|SkinProtagonistFace[]
     */
    public function getFaces(): Collection
    {
        return $this->faces;
    }

    public function addFace(SkinProtagonistFace $face): self
    {
        if (!$this->faces->contains($face)) {
            $this->faces[] = $face;
        }

        return $this;
    }

    public function removeFace(SkinProtagonistFace $face): self
    {
        $this->faces->removeElement($face);

        return $this;
    }

    /**
     * @return Collection|SkinProtagonistAccessory[]
     */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(SkinProtagonistAccessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories[] = $accessory;
        }

        return $this;
    }

    public function removeAccessory(SkinProtagonistAccessory $accessory): self
    {
        $this->accessories->removeElement($accessory);

        return $this;
    }
}
