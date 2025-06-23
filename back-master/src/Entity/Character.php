<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 */
class Character extends BaseEntity
{
    const GENDERS = ["male", "female"];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Choice(
     *  choices=Character::GENDERS,
     *  message="character.gender.not_valid"
     * )
     */
    private $gender;

    /**
     * @ORM\ManyToMany(targetEntity=SkinCharacterHead::class, inversedBy="characters")
     */
    private $headSkins;

    /**
     * @ORM\ManyToMany(targetEntity=SkinCharacterBody::class, inversedBy="characters")
     */
    private $bodySkins;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->headSkins = new ArrayCollection();
        $this->bodySkins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|SkinCharacterHead[]
     */
    public function getHeadSkins(): Collection
    {
        return $this->headSkins;
    }

    public function addHeadSkin(SkinCharacterHead $headSkin): self
    {
        if (!$this->headSkins->contains($headSkin)) {
            $this->headSkins[] = $headSkin;
        }

        return $this;
    }

    public function removeHeadSkin(SkinCharacterHead $headSkin): self
    {
        $this->headSkins->removeElement($headSkin);

        return $this;
    }

    /**
     * @return Collection|SkinCharacterBody[]
     */
    public function getBodySkins(): Collection
    {
        return $this->bodySkins;
    }

    public function addBodySkin(SkinCharacterBody $bodySkin): self
    {
        if (!$this->bodySkins->contains($bodySkin)) {
            $this->bodySkins[] = $bodySkin;
        }

        return $this;
    }

    public function removeBodySkin(SkinCharacterBody $bodySkin): self
    {
        $this->bodySkins->removeElement($bodySkin);

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
