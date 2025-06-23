<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    public $manager;

    /**
     * @var ValidatorInterface
     */
    public $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    /**
     * Create an user
     *
     * @Route("/api/users", name="api_user_create", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        // Get data
        $email = $request->request->get("email", "");
        $pseudo = $request->request->get("pseudo", "");
        $password = $request->request->get("password", "");

        // Set the user
        $user = new User();

        $user
            ->setEmail($email)
            ->setPseudo($pseudo)
            ->setPassword($password);

        // Check the errors
        $validatorService = new ValidationService($this->validator, $user);
        if (!$validatorService->isValid($user)) {
            return $this->json($validatorService->getViolations($user), Response::HTTP_BAD_REQUEST);
        }

        // Encode the password
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

        // Manager
        $this->manager->persist($user);
        $this->manager->flush();

        // Response
        return $this->json($user, Response::HTTP_CREATED, [], ["groups" => "user:read"]);
    }

    /**
     * Create an user
     *
     * @Route("/api/users", name="api_user_update", methods={"UPDATE"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(Request $request): JsonResponse
    {
        // Get data
        $email = $request->request->get("email", "");
        $pseudo = $request->request->get("pseudo", "");

        // Set the user
        $user = new User();

        $user
            ->setEmail($email)
            ->setPseudo($pseudo);

        // Check the errors
        $validatorService = new ValidationService($this->validator, $user);
        if (!$validatorService->isValid($user)) {
            return $this->json($validatorService->getViolations($user), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($user);
        $this->manager->flush();

        // Response
        return $this->json($user, Response::HTTP_OK, [], ["groups" => "user:read"]);
    }
}
