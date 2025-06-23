<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SettingRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
class Setting extends AbstractController
{
    const LANGUAGES = ["fr_FR"];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read", "setting:read"})
     *
     * @Assert\NotBlank(
     *  message="setting.volume_music.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="setting.volume_music.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=100,
     *  message="setting.volume_music.too_high"
     * )
     */
    private $volumeMusic = 50;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read", "setting:read"})
     *
     * @Assert\NotBlank(
     *  message="setting.volume_ambiance.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="setting.volume_ambiance.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=100,
     *  message="setting.volume_ambiance.too_high"
     * )
     */
    private $volumeAmbiance = 50;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read", "setting:read"})
     *
     * @Assert\NotBlank(
     *  message="setting.volume_effects.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="setting.volume_effects.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=100,
     *  message="setting.volume_effects.too_high"
     * )
     */
    private $volumeEffects = 50;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Choice(
     *  choices=Setting::LANGUAGES,
     *  message="setting.language.not_valid"
     * )
     */
    private $language = "fr_FR";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolumeMusic(): ?int
    {
        return $this->volumeMusic;
    }

    public function setVolumeMusic($volumeMusic): self
    {
        $this->volumeMusic = $volumeMusic;

        return $this;
    }

    public function getVolumeAmbiance(): ?int
    {
        return $this->volumeAmbiance;
    }

    public function setVolumeAmbiance($volumeAmbiance): self
    {
        $this->volumeAmbiance = $volumeAmbiance;

        return $this;
    }

    public function getVolumeEffects(): ?int
    {
        return $this->volumeEffects;
    }

    public function setVolumeEffects($volumeEffects): self
    {
        $this->volumeEffects = $volumeEffects;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }
}
