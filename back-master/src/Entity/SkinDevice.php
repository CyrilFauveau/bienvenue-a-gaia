<?php

namespace App\Entity;

use App\Repository\SkinDeviceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkinDeviceRepository::class)
 */
class SkinDevice extends Skin
{
    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Device::class, inversedBy="skins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $device;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }
}
