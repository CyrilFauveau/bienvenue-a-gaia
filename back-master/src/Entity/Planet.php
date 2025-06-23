<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanetRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=PlanetRepository::class)
 *
 * @UniqueEntity(fields="name", message="planet.name.not_unique")
 */
class Planet extends AbstractController
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"planet:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Groups({"planet:read"})
     *
     * @Assert\NotBlank(
     *  message="planet.name.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=100,
     *  minMessage="planet.name.too_short",
     *  maxMessage="planet.name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"planet:read"})
     *
     * @Assert\NotBlank(
     *  message="planet.description.not_blank"
     * )
     * @Assert\Length(
     *  min=10,
     *  minMessage="planet.description.too_short",
     * )
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Region::class, mappedBy="planet")
     *
     * @Groups({"planet:read"})
     */
    private $regions;

    /**
     * @ORM\OneToMany(targetEntity=Voice::class, mappedBy="planet")
     *
     * @Groups({"planet:read"})
     */
    private $voices;

    /**
     * @ORM\OneToOne(targetEntity=File::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"planet:read"})
     */
    private $file;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
        $this->voices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
            $region->setPlanet($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getPlanet() === $this) {
                $region->setPlanet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Voice[]
     */
    public function getVoices(): Collection
    {
        return $this->voices;
    }

    public function addVoice(Voice $voice): self
    {
        if (!$this->voices->contains($voice)) {
            $this->voices[] = $voice;
            $voice->setPlanet($this);
        }

        return $this;
    }

    public function removeVoice(Voice $voice): self
    {
        if ($this->voices->removeElement($voice)) {
            // set the owning side to null (unless already changed)
            if ($voice->getPlanet() === $this) {
                $voice->setPlanet(null);
            }
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
