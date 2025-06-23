<?php

namespace App\Controller;

use App\Service\ParametersService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingController extends AbstractController
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
     * Set the volumes
     *
     * @Route("/api/setting/volumes", name="api_setting_update_volumes", methods={"PUT"})
     *
     * @param ParametersService $parametersService
     * @return JsonResponse
     */
    public function updateVolumes(ParametersService $parametersService): JsonResponse
    {
        // Get the data in the request
        $volumeEffects = $parametersService->getParameterInRequest("volumeEffects");
        $volumeAmbiance = $parametersService->getParameterInRequest("volumeAmbiance");
        $volumeMusic = $parametersService->getParameterInRequest("volumeMusic");

        // Set the setting
        $setting = $this->getUser()->getSetting();
        $setting
            ->setVolumeEffects($volumeEffects)
            ->setVolumeAmbiance($volumeAmbiance)
            ->setVolumeMusic($volumeMusic);

        // Check the errors
        $validatorService = new ValidationService($this->validator, [$setting]);
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($setting);
        $this->manager->flush();

        // Response
        return $this->json($setting, Response::HTTP_OK, [], ["groups" => "setting:read"]);
    }
}
