<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Repository\FileRepository;
use App\Service\ParametersService;
use App\Service\ValidationService;
use App\Repository\VoiceRepository;
use App\Repository\PlanetRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanetController extends AbstractController
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
     * @var PlanetRepository
     */
    public $planetRepository;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, PlanetRepository $planetRepository)
    {
        $this->manager = $manager;
        $this->validator = $validator;

        $this->planetRepository = $planetRepository;
    }

    /**
     * Get a planet
     *
     * @Route("/api/public/planet/{id}", name="api_public_planet_get", methods={"GET"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getPlanet(int $id): JsonResponse
    {
        // Get the planet according the id
        $planet = $this->planetRepository->findOneBy(["id" => $id]);
        if (!$planet) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Response
        return $this->json($planet, Response::HTTP_OK, [], ["groups" => "planet:read"]);
    }

    /**
     * Get the planets
     *
     * @Route("/api/public/planets", name="api_public_planets_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getPlanets(): JsonResponse
    {
        // Get the planets
        $planets = $this->planetRepository->findBy([], ["name" => "asc"]);

        // Response
        return $this->json($planets, Response::HTTP_OK, [], ["groups" => "planet:read"]);
    }

    /**
     * Add a planet
     *
     * @Route("/api/admin/planet", name="api_admin_planet_add", methods={"POST"})
     *
     * @param ParametersService $parametersService
     * @param RegionRepository $regionRepository
     * @param VoiceRepository $voiceRepository
     * @param FileRepository $fileRepository
     * @return JsonResponse
     */
    public function addPlanet(ParametersService $parametersService, RegionRepository $regionRepository, VoiceRepository $voiceRepository, FileRepository $fileRepository): JsonResponse
    {
        // Get the data in the request
        $name = $parametersService->getParameterInRequest("name");
        $fileId = $parametersService->getParameterInRequest("fileId");
        $description = $parametersService->getParameterInRequest("description");
        $regionsId = (array) $parametersService->getParameterInRequest("regionsId", []);
        $voicesId = (array) $parametersService->getParameterInRequest("voicesId", []);

        // Set the planet
        $planet = new Planet();
        $planet
            ->setName($name)
            ->setDescription($description);

        // Init the errors
        $validatorService = new ValidationService($this->validator, [$planet]);

        // Set the image
        if ($fileId) {
            $file = $fileRepository->findOneBy(["id" => $fileId]);
            if ($file) {
                $planet->setFile($file);
            } else {
                $validatorService->addViolation("planet.file.not_exist", ["id" => $fileId]);
            }
        } else {
            $validatorService->addViolation("planet.file.not_blank");
        }

        // Add regions
        foreach ($regionsId as $id) {
            $region = $regionRepository->findOneBy(["id" => $id]);
            if (!$region) {
                $validatorService->addViolation("planet.region.not_exist", ["id" => $id]);
            } else {
                $planet->addRegion($region);
            }
        }

        // Add voices
        foreach ($voicesId as $id) {
            $voice = $voiceRepository->findOneBy(["id" => $id]);
            if (!$voice) {
                $validatorService->addViolation("planet.voice.not_exist", ["id" => $id]);
            } else {
                $planet->addVoice($voice);
            }
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($planet);
        $this->manager->flush();

        // Response
        return $this->json($planet, Response::HTTP_CREATED, [], ["groups" => "planet:read"]);
    }

    /**
     * Update a planet
     *
     * @Route("/api/admin/planet/{id}", name="api_admin_planet_update", methods={"PUT"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @param ParametersService $parametersService
     * @param RegionRepository $regionRepository
     * @param VoiceRepository $voiceRepository
     * @param FileRepository $fileRepository
     * @return JsonResponse
     */
    public function updatePlanet(int $id, ParametersService $parametersService, RegionRepository $regionRepository, VoiceRepository $voiceRepository, FileRepository $fileRepository): JsonResponse
    {
        // Get the planet according the id
        $planet = $this->planetRepository->findOneBy(["id" => $id]);
        if (!$planet) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Get the data
        $name = $parametersService->getParameterInRequest("name");
        $fileId = $parametersService->getParameterInRequest("fileId");
        $description = $parametersService->getParameterInRequest("description", "");
        $regionsId = (array) $parametersService->getParameterInRequest("regionsId", []);
        $voicesId = (array) $parametersService->getParameterInRequest("voicesId", []);

        // Set the planet
        $planet
            ->setName($name)
            ->setDescription($description);

        // Init the errors
        $validatorService = new ValidationService($this->validator, [$planet]);

        // Set the image
        if ($fileId) {
            $file = $fileRepository->findOneBy(["id" => $fileId]);
            if ($file) {
                $planet->setFile($file);
            } else {
                $validatorService->addViolation("planet.file.not_exist", ["id" => $id]);
            }
        } else {
            $validatorService->addViolation("planet.file.not_blank");
        }

        // Remove old and add new regions
        foreach ($planet->getRegions() as $region) {
            $planet->removeRegion($region);
        }
        foreach ($regionsId as $id) {
            $region = $regionRepository->findOneBy(["id" => $id]);
            if (!$region) {
                $validatorService->addViolation("planet.region.not_exist", ["id" => $id]);
            } else {
                $planet->addRegion($region);
            }
        }

        // Remove old and add new voices
        foreach ($planet->getVoices() as $voice) {
            $planet->removeVoice($voice);
        }
        foreach ($voicesId as $id) {
            $voice = $voiceRepository->findOneBy(["id" => $id]);
            if (!$voice) {
                $validatorService->addViolation("planet.voice.not_exist", ["id" => $id]);
            } else {
                $planet->addVoice($voice);
            }
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($planet);
        $this->manager->flush();

        // Response
        return $this->json($planet, Response::HTTP_OK, [], ["groups" => "planet:read"]);
    }

    /**
     * Remove a planet
     *
     * @Route("/api/admin/planet/{id}", name="api_admin_planet_remove", methods={"DELETE"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function removePlanet(int $id): JsonResponse
    {
        // Get the planet according the id
        $planet = $this->planetRepository->findOneBy(["id" => $id]);
        if (!$planet) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Manager
        $this->manager->remove($planet);
        $this->manager->flush();

        // Response
        return $this->json([], Response::HTTP_OK);
    }
}
