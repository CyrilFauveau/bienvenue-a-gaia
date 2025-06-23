<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FileCategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=FileCategoryRepository::class)
 *
 * @UniqueEntity(fields="name", message="file_category.name.not_unique")
 * @UniqueEntity(fields="slug", message="file_category.slug.not_unique")
 */
class FileCategory extends AbstractController
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"file:read", "fileCategory:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"file:read", "fileCategory:read"})
     *
     * @Assert\NotBlank(
     *  message="file_category.name.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=255,
     *  minMessage="file_category.name.too_short",
     *  maxMessage="file_category.name.too_long"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="category", cascade={"remove"})
     *
     * @Groups({"fileCategory:read"})
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"file:read", "fileCategory:read"})
     *
     * @Assert\NotBlank(
     *  message="file_category.slug.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  max=255,
     *  minMessage="file_category.slug.too_short",
     *  maxMessage="file_category.slug.too_long"
     * )
     */
    private $slug;

    public function __construct()
    {
        $this->files = new ArrayCollection();
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
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setCategory($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getCategory() === $this) {
                $file->setCategory(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
