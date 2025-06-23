<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
  /**
   * @var ValidatorInterface
   */
  private $validator;

  /**
   * @var array
   */
  protected $violations;

  /**
   * @var array
   */
  protected $entities;

  public function __construct(ValidatorInterface $validator, array $entities = [])
  {
    $this->violations["errors"] = [];
    $this->validator = $validator;
    $this->entities = $entities;
  }

  /**
   * Check if there is no validation error
   *
   * @return bool
   */
  public function isValid(): bool
  {
    if (count($this->entities)) {
      $this->generateViolationsWithAssert();
    }
    return empty($this->violations["errors"]);
  }

  /**
   * Get all the violations
   *
   * @return array
   */
  public function getViolations(): array
  {
    return $this->violations;
  }

  /**
   * Get the violations generate with the assert in the entity
   *
   * @return void
   */
  private function generateViolationsWithAssert(): void
  {
    foreach ($this->entities as $entity) {
      $violationsWithAssert = $this->validator->validate($entity);
      foreach ($violationsWithAssert as $violation) {
        $this->addViolation($violation->getMessage(), $violation->getParameters());
      }
    }
  }

  /**
   * Check if a message is already exist for a specific property path
   *
   * @param string $propertyName
   * @param string $message
   * @return bool
   */
  private function messageAlreadyExists($propertyName, $message): bool
  {
    if (array_key_exists($propertyName, $this->violations["errors"])) {
      if (in_array($message, array_column($this->violations["errors"][$propertyName], "message"))) {
        return true;
      }
    }
    return false;
  }

  /**
   * Add a violation
   *
   * @param string $message
   * @param array $parameters
   * @return void
   */
  public function addViolation(string $message, array $parameters = []): void
  {
    list($entityName, $propertyName) = explode(".", $message);
    if (!$this->messageAlreadyExists($propertyName, $message)) {
      $this->violations["errors"][$entityName][$propertyName][] = ["message" => $message, "parameters" => $parameters];
    }
  }
}
