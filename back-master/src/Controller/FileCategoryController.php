<?php

namespace App\Controller;

use App\Entity\FileCategory;
use App\Service\StringService;
use App\Service\ParametersService;
use App\Service\ValidationService;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FileCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileCategoryController extends AbstractController
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
     * @var FileCategoryRepository
     */
    public $fileCategoryRepository;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, FileCategoryRepository $fileCategoryRepository)
    {
        $this->manager = $manager;
        $this->validator = $validator;

        $this->fileCategoryRepository = $fileCategoryRepository;
    }

    /**
     * Get a category for file
     *
     * @Route("/api/admin/filecategory/{id}", name="api_admin_filecategory_get", methods={"GET"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getFileCategory(int $id): JsonResponse
    {
        // Get the file according the id
        $fileCategory = $this->fileCategoryRepository->findOneBy(["id" => $id]);
        if (!$fileCategory) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Response
        return $this->json($fileCategory, Response::HTTP_OK, [], ["groups" => "fileCategory:read"]);
    }

    /**
     * Get the categories for file
     *
     * @Route("/api/admin/filecategories", name="api_admin_filecategories_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getFileCategories(): JsonResponse
    {
        // Get the fileCategories
        $fileCategories = $this->fileCategoryRepository->findBy([], ["name" => "asc"]);

        // Response
        return $this->json($fileCategories, Response::HTTP_OK, [], ["groups" => "fileCategory:read"]);
    }

    /**
     * Add a category for file
     *
     * @Route("/api/admin/filecategory", name="api_admin_filecategory_add", methods={"POST"})
     *
     * @param ParametersService $parametersService
     * @return JsonResponse
     */
    public function addFileCategory(ParametersService $parametersService, StringService $stringService): JsonResponse
    {
        // Get the data in the request
        $name = $parametersService->getParameterInRequest("name");

        // Set the fileCategory
        $fileCategory = new FileCategory();
        $fileCategory
            ->setName($name)
            ->setSlug($stringService->slugify($name));

        // Init the FileUploader
        $validatorService = new ValidationService($this->validator, [$fileCategory]);

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($fileCategory);
        $this->manager->flush();

        // Response
        return $this->json($fileCategory, Response::HTTP_CREATED, [], ["groups" => "fileCategory:read"]);
    }

    /**
     * Update a category for file
     *
     * @Route("/api/admin/filecategory/{id}", name="api_admin_filecategory_update", methods={"PUT"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @param ParametersService $parametersService
     * @param StringService $stringService
     * @return JsonResponse
     */
    public function updateFileCategory(int $id, ParametersService $parametersService, StringService $stringService): JsonResponse
    {
        // Get the fileCategory according the id
        $fileCategory = $this->fileCategoryRepository->findOneBy(["id" => $id]);
        if (!$fileCategory) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Get the data in the request
        $name = $parametersService->getParameterInRequest("name");

        $oldSlug = $fileCategory->getSlug();

        // Set the fileCategory
        $fileCategory
            ->setName($name)
            ->setSlug($stringService->slugify($name));

        // Init the errors
        $validatorService = new ValidationService($this->validator, [$fileCategory]);

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Rename the old folder
        $oldTargetDirectory = $this->getParameter("uploads_directory") . "/" . $oldSlug;
        $newTargetDirectory = $this->getParameter("uploads_directory") . "/" . $fileCategory->getSlug();
        $fileUploader = new FileUploaderService($oldTargetDirectory);
        $fileUploader->renameFolder($newTargetDirectory);

        // Manager
        $this->manager->persist($fileCategory);
        $this->manager->flush();

        // Response
        return $this->json($fileCategory, Response::HTTP_OK, [], ["groups" => "fileCategory:read"]);
    }

    /**
     * Remove a category for file
     *
     * @Route("/api/admin/filecategory/{id}", name="api_admin_filecategory_remove", methods={"DELETE"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function removeFileCategory(int $id): JsonResponse
    {
        // Get the fileCategory according the id
        $fileCategory = $this->fileCategoryRepository->findOneBy(["id" => $id]);
        if (!$fileCategory) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Init the FileUploader
        $targetDirectory = $this->getParameter("uploads_directory") . "/" . $fileCategory->getSlug();
        $fileUploader = new FileUploaderService($targetDirectory);

        // Remove the files
        foreach ($fileCategory->getFiles() as $file) {
            $fileUploader->removeFile($file->getName());
        }

        // Remove the folder
        $fileUploader->removeFolder();

        // Manager
        $this->manager->remove($fileCategory);
        $this->manager->flush();

        // Response
        return $this->json([], Response::HTTP_OK);
    }
}
