<?php

namespace App\Controller;

use App\Entity\Voice;
use App\Repository\FileRepository;
use App\Service\ParametersService;
use App\Service\ValidationService;
use App\Repository\VoiceRepository;
use App\Repository\PlanetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoiceController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    public $manager;

    /**
     * @var ValidatorInterface
     */
    public $validator;

    /**
     * @var VoiceRepository
     */
    public $voiceRepository;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, VoiceRepository $voiceRepository)
    {
        $this->manager = $manager;
        $this->validator = $validator;

        $this->voiceRepository = $voiceRepository;
    }

    /**
     * Get a voice
     *
     * @Route("/api/admin/voice/{id}", name="api_admin_voice_get", methods={"GET"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getVoice(int $id): JsonResponse
    {
        // Get the file according the id
        $voice = $this->voiceRepository->findOneBy(["id" => $id]);
        if (!$voice) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Response
        return $this->json($voice, Response::HTTP_OK, [], ["groups" => "voice:read"]);
    }

    /**
     * Get the voices
     *
     * @Route("/api/admin/voices", name="api_admin_voices_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getVoices(): JsonResponse
    {
        // Get the voices
        $voices = $this->voiceRepository->findBy([], ["name" => "asc"]);

        // Response
        return $this->json($voices, Response::HTTP_OK, [], ["groups" => "voice:read"]);
    }

    /**
     * Get voices by planet
     *
     * @Route("/api/public/voices/planet/{id}", name="api_public_voices_planet_get", methods={"GET"}, requirements={"id"="[0-9]+"})
     *
     * @param integer $id
     * @param ParametersService $parametersService
     * @param PlanetRepository $planetRepository
     * @return JsonResponse
     */
    public function getVoicesByPlanet(int $id, ParametersService $parametersService, PlanetRepository $planetRepository): JsonResponse
    {
        // Get the planet
        $planet = $planetRepository->find($id);
        if (!$planet) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Get the data in the request
        $gender = $parametersService->getParameterInQuery("gender");

        // Criteria
        $criteria = ["planet" => $planet];
        if ($gender) {
            $criteria["gender"] = $gender;
        }

        // Get the voices
        $voices = $this->voiceRepository->findBy($criteria);

        // Init errors
        $validatorService = new ValidationService($this->validator, [$planet]);

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Response
        return $this->json($voices, Response::HTTP_OK, [], ["groups" => "voice:read"]);
    }

    /**
     * Add a voice
     *
     * @Route("/api/admin/voice", name="api_admin_voice_add", methods={"POST"})
     *
     * @param ParametersService $parametersService
     * @param PlanetRepository $planetRepository
     * @param FileRepository $fileRepository
     * @return JsonResponse
     */
    public function addVoice(ParametersService $parametersService, PlanetRepository $planetRepository, FileRepository $fileRepository): JsonResponse
    {
        // Get the data in the request
        $name = $parametersService->getParameterInRequest("name");
        $gender = $parametersService->getParameterInRequest("gender");
        $planetId = $parametersService->getParameterInRequest("planetId");
        $fileId = $parametersService->getParameterInRequest("fileId");

        // Set the voice
        $voice = new Voice();
        $voice
            ->setName($name)
            ->setGender($gender);

        // Init errors
        $validatorService = new ValidationService($this->validator, [$voice]);

        // Set the planet
        $planet = $planetRepository->findOneBy(["id" => $planetId]);
        if (!$planetId) {
            $validatorService->addViolation("voice.planet.not_blank");
        }
        if (!$planet) {
            $validatorService->addViolation("voice.planet.not_exist", ["id" => $planetId]);
        } else {
            $voice->setPlanet($planet);
        }

        // Set the file
        $file = $fileRepository->findOneBy(["id" => $fileId]);
        if (!$fileId) {
            $validatorService->addViolation("voice.file.not_blank");
        }
        if (!$file) {
            $validatorService->addViolation("voice.file.not_exist", ["id" => $fileId]);
        } else {
            $voice->setFile($file);
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($voice);
        $this->manager->flush();

        // Response
        return $this->json($voice, Response::HTTP_CREATED, [], ["groups" => "voice:read"]);
    }

    /**
     * Update a voice
     *
     * @Route("/api/admin/voice/{id}", name="api_admin_voice_update", methods={"PUT"}, requirements={"id"="[0-9]+"})
     *
     * @param integer $id
     * @param ParametersService $parametersService
     * @param PlanetRepository $planetRepository
     * @param FileRepository $fileRepository
     * @return JsonResponse
     */
    public function updateVoice(int $id, ParametersService $parametersService, PlanetRepository $planetRepository, FileRepository $fileRepository): JsonResponse
    {
        // Get the voice
        $voice = $this->voiceRepository->findOneBy(["id" => $id]);
        if (!$voice) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Get the data in the request
        $name = $parametersService->getParameterInRequest("name");
        $gender = $parametersService->getParameterInRequest("gender");
        $planetId = $parametersService->getParameterInRequest("planetId");
        $fileId = $parametersService->getParameterInRequest("fileId");

        // Init errors
        $validatorService = new ValidationService($this->validator, [$voice]);

        // Set the voice
        $voice
            ->setName($name)
            ->setGender($gender);

        // Set the planet
        $planet = $planetRepository->findOneBy(["id" => $planetId]);
        if (!$planetId) {
            $validatorService->addViolation("voice.planet.not_blank");
        }
        if (!$planet) {
            $validatorService->addViolation("voice.planet.not_exist", ["id" => $planetId]);
        } else {
            $voice->setPlanet($planet);
        }

        // Set the file
        $file = $fileRepository->findOneBy(["id" => $fileId]);
        if (!$fileId) {
            $validatorService->addViolation("voice.file.not_blank");
        }
        if (!$file) {
            $validatorService->addViolation("voice.file.not_exist", ["id" => $fileId]);
        } else {
            $voice->setFile($file);
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($voice);
        $this->manager->flush();

        // Response
        return $this->json($voice, Response::HTTP_OK, [], ["groups" => "voice:read"]);
    }

    /**
     * Remove a voice
     *
     * @Route("/api/admin/voice/{id}", name="api_admin_voice_remove", methods={"DELETE"}, requirements={"id"="[0-9]+"})
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function removeVoice(int $id): JsonResponse
    {
        // Get the voice according the id
        $voice = $this->voiceRepository->findOneBy(["id" => $id]);
        if (!$voice) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Manager
        $this->manager->remove($voice);
        $this->manager->flush();

        // Response
        return $this->json([], Response::HTTP_OK);
    }
}
