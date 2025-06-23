<?php

namespace App\Entity;

use App\Repository\AllSkinsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AllSkinsRepository::class)
 */
class AllSkins
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=SkinBackground::class)
     */
    private $backgroundSkins;

    /**
     * @ORM\ManyToMany(targetEntity=Device::class)
     */
    private $deviceSkins;

    /**
     * @ORM\OneToOne(targetEntity=SkinsProtagonist::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $protagonistSkins;

    public function __construct()
    {
        $this->backgroundSkins = new ArrayCollection();
        $this->deviceSkins = new ArrayCollection();

        $this->setProtagonistSkins(new SkinsProtagonist());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|SkinBackground[]
     */
    public function getBackgroundSkins(): Collection
    {
        return $this->backgroundSkins;
    }

    public function addBackgroundSkin(SkinBackground $backgroundSkin): self
    {
        if (!$this->backgroundSkins->contains($backgroundSkin)) {
            $this->backgroundSkins[] = $backgroundSkin;
        }

        return $this;
    }

    public function removeBackgroundSkin(SkinBackground $backgroundSkin): self
    {
        $this->backgroundSkins->removeElement($backgroundSkin);

        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDeviceSkins(): Collection
    {
        return $this->deviceSkins;
    }

    public function addDeviceSkin(Device $deviceSkin): self
    {
        if (!$this->deviceSkins->contains($deviceSkin)) {
            $this->deviceSkins[] = $deviceSkin;
        }

        return $this;
    }

    public function removeDeviceSkin(Device $deviceSkin): self
    {
        $this->deviceSkins->removeElement($deviceSkin);

        return $this;
    }

    public function getProtagonistSkins(): ?SkinsProtagonist
    {
        return $this->protagonistSkins;
    }

    public function setProtagonistSkins(SkinsProtagonist $protagonistSkins): self
    {
        $this->protagonistSkins = $protagonistSkins;

        return $this;
    }
}
