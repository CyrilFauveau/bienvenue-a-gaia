<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FileRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 *
 * @UniqueEntity(fields="title", message="file.title.not_unique")
 */
class File extends AbstractController
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"file:read", "fileCategory:read", "planet:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"file:read", "fileCategory:read", "planet:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Groups({"file:read", "fileCategory:read"})
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"file:read", "fileCategory:read", "planet:read"})
     *
     * @Assert\NotBlank(
     *  message="file.title.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=255,
     *  minMessage="file.title.too_short",
     *  maxMessage="file.title.too_long"
     * )
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=FileCategory::class, inversedBy="files")
     *
     * @Groups({"file:read"})
     */
    private $category;

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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?FileCategory
    {
        return $this->category;
    }

    public function setCategory(?FileCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
