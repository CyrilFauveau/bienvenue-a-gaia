<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoiceRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=VoiceRepository::class)
 *
 * @UniqueEntity(fields="name", message="voice.name.not_unique")
 */
class Voice extends AbstractController
{
    const GENDERS = ["male", "female"];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"voice:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Groups({"voice:read"})
     *
     * @Assert\NotBlank(
     *  message="voice.name.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=100,
     *  minMessage="voice.name.too_short",
     *  maxMessage="voice.name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Groups({"voice:read"})
     *
     * @Assert\Choice(
     *  choices=Voice::GENDERS,
     *  message="voice.gender.not_valid"
     * )
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity=Planet::class, inversedBy="voices")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"voice:read"})
     */
    private $planet;

    /**
     * @ORM\OneToOne(targetEntity=File::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"voice:read"})
     */
    private $file;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender($gender): self
    {
        $this->gender = $gender;

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
