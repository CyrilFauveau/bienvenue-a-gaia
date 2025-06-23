<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 *
 * @UniqueEntity(fields="name", message="badge.name.not_unique")
 */
class Device extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(
     *  message="device.name.not_blank"
     * )
     * @Assert\Length(
     *  min=3,
     *  max=100,
     *  minMessage="device.name.too_short",
     *  maxMessage="device.name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=SkinDevice::class, mappedBy="device")
     */
    private $skins;

    public function __construct()
    {
        $this->skins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|SkinDevice[]
     */
    public function getSkins(): Collection
    {
        return $this->skins;
    }

    public function addSkin(SkinDevice $skin): self
    {
        if (!$this->skins->contains($skin)) {
            $this->skins[] = $skin;
            $skin->setDevice($this);
        }

        return $this;
    }

    public function removeSkin(SkinDevice $skin): self
    {
        if ($this->skins->removeElement($skin)) {
            // set the owning side to null (unless already changed)
            if ($skin->getDevice() === $this) {
                $skin->setDevice(null);
            }
        }

        return $this;
    }
}
