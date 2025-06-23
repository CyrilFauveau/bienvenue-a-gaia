<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 *
 * @UniqueEntity(fields="name", message="region.name.not_unique")
 */
class Region extends BaseEntity
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
     *  message="region.name.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=100,
     *  minMessage="region.name.too_short",
     *  maxMessage="region.name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Character::class, mappedBy="region")
     */
    private $characters;

    /**
     * @ORM\ManyToOne(targetEntity=Planet::class, inversedBy="regions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planet;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
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
            $character->setRegion($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getRegion() === $this) {
                $character->setRegion(null);
            }
        }

        return $this;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): self
    {
        $this->planet = $planet;

        return $this;
    }
}
