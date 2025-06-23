<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait SkinPurchasable
{
    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *  message="user.skin_purchasable.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=1,
     *  message="user.skin_purchasable.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=9999999,
     *  message="user.skin_purchasable.too_high"
     * )
     */
    private $money;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank(
     *  message="user.skin_purchasable.not_blank"
     * )
     * @Assert\GreaterThanOrEqual(
     *  value=0,
     *  message="user.skin_purchasable.too_low"
     * )
     * @Assert\LessThanOrEqual(
     *  value=30,
     *  message="user.skin_purchasable.too_high"
     * )
     */
    private $level;

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(int $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
