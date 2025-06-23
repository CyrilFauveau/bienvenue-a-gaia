<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *  message="game.score.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="game.score.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=9999999,
     *  message="game.score.too_high"
     * )
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime(
     *  message="game.datetime_start.not_valid"
     * )
     */
    private $datetimeStart;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *  message="game.datetime_end.not_valid"
     * )
     */
    private $datetimeEnd;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *  message="game.good_choices.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="game.good_choices.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=9999999,
     *  message="game.good_choices.too_high"
     * )
     */
    private $goodChoices;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *  message="game.bad_choices.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="game.bad_choices.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=9999999,
     *  message="game.bad_choices.too_high"
     * )
     */
    private $badChoices;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDatetimeStart(): ?\DateTimeInterface
    {
        return $this->datetimeStart;
    }

    public function setDatetimeStart(\DateTimeInterface $datetimeStart): self
    {
        $this->datetimeStart = $datetimeStart;

        return $this;
    }

    public function getDatetimeEnd(): ?\DateTimeInterface
    {
        return $this->datetimeEnd;
    }

    public function setDatetimeEnd(\DateTimeInterface $datetimeEnd): self
    {
        $this->datetimeEnd = $datetimeEnd;

        return $this;
    }

    public function getGoodChoices(): ?int
    {
        return $this->goodChoices;
    }

    public function setGoodChoices(int $goodChoices): self
    {
        $this->goodChoices = $goodChoices;

        return $this;
    }

    public function getBadChoices(): ?int
    {
        return $this->badChoices;
    }

    public function setBadChoices(int $badChoices): self
    {
        $this->badChoices = $badChoices;

        return $this;
    }
}
