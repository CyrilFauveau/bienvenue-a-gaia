<?php

namespace App\Events;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
  public function updateJwtData(JWTCreatedEvent $event)
  {
    /** @var User */
    $user = $event->getUser();

    // Get the data in the request
    $data = $event->getData();

    // Set the data with the user's data
    $data["id"] = $user->getId();
    $data["pseudo"] = $user->getPseudo();
    $data["money"] = $user->getMoney();
    $data["level"] = $user->getLevel();
    $data["bestScore"] = $user->getBestScore();
    $data["experience"] = $user->getExperience();

    // Delete data
    unset($data["roles"]);

    $event->setData($data);
  }
}
