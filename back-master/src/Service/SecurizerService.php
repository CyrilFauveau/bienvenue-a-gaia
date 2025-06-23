<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class SecurizerService
{

    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * Find out if a user has a specific role
     *
     * @param User $user
     * @param string $attribute
     * @return bool
     */
    public function isGranted(User $user, string $attribute): bool
    {
        $token = new UsernamePasswordToken($user, "none", "none", $user->getRoles());
        return $this->accessDecisionManager->decide($token, [$attribute]);
    }
}
