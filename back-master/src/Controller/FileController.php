<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\FileCategory;
use App\Repository\FileRepository;
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

class FileController extends AbstractController
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
     * @var FileRepository
     */
    public $fileRepository;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, FileRepository $fileRepository)
    {
        $this->manager = $manager;
        $this->validator = $validator;

        $this->fileRepository = $fileRepository;
    }

    /**
     * Get a file
     *
     * @Route("/api/admin/file/{id}", name="api_admin_file_get", methods={"GET"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getFile(int $id): JsonResponse
    {
        // Get the file according the id
        $file = $this->fileRepository->findOneBy(["id" => $id]);
        if (!$file) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Response
        return $this->json($file, Response::HTTP_OK, [], ["groups" => "file:read"]);
    }

    /**
     * Get the files
     *
     * @Route("/api/admin/files", name="api_admin_files_get", methods={"GET"})
     *
     * @param ParametersService $parametersService
     * @return JsonResponse
     */
    public function getFiles(ParametersService $parametersService): JsonResponse
    {
        // Get the data in the request
        $isUnCategorized = $parametersService->getParameterInQuery("isUnCategorized", false);

        // Criteria
        $criteria = [];
        if ($isUnCategorized) {
            $criteria["category"] = null;
        }

        // Get the files
        $files = $this->fileRepository->findBy($criteria);

        // Response
        return $this->json($files, Response::HTTP_OK, [], ["groups" => "file:read"]);
    }

    /**
     * Upload a file
     *
     * @Route("/api/admin/file", name="api_admin_file_add", methods={"POST"})
     *
     * @param ParametersService $parametersService
     * @param FileCategoryRepository $fileCategoryRepository
     * @return JsonResponse
     */
    public function addFile(ParametersService $parametersService, FileCategoryRepository $fileCategoryRepository): JsonResponse
    {
        // Get the data in the request
        $uploadFile = $parametersService->getFile("file");
        $fileCategoryId = $parametersService->getParameterInRequest("fileCategoryId");
        $title = $parametersService->getParameterInRequest("title");

        // Set the file
        $file = new File();
        $file->setTitle($title);

        // Init the errors
        $validatorService = new ValidationService($this->validator, [$file]);

        // Get the category
        $fileCategory = $fileCategoryRepository->findOneBy(["id" => $fileCategoryId]);
        if (!$fileCategory && $fileCategoryId) {
            $validatorService->addViolation("file.fileCategory.not_exist");
        }

        // Init the FileUploader
        $fileUploader = new FileUploaderService($this->getPathToFolder($fileCategory));

        // Upload the file
        if ($uploadFile) {
            list($fileName, $extension) = $fileUploader->uploadFile($uploadFile);
            if ($fileName) {
                $file
                    ->setName($fileName)
                    ->setCategory($fileCategory)
                    ->setExtension($extension);
            } else {
                $validatorService->addViolation("file.file.not_valid", ["fileName" => $fileName]);
            }
        } else {
            $validatorService->addViolation("file.file.not_blank");
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            if (isset($fileName)) {
                $fileUploader->removeFile($fileName);
            }
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Manager
        $this->manager->persist($file);
        $this->manager->flush();

        // Response
        return $this->json($file, Response::HTTP_OK, [], ["groups" => "file:read"]);
    }

    /**
     * Update a file
     *
     * @Route("/api/admin/file/{id}", name="api_admin_file_update", methods={"PUT"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @param ParametersService $parametersService
     * @param FileCategoryRepository $fileCategoryRepository
     * @return JsonResponse
     */
    public function updateFile(int $id, ParametersService $parametersService, FileCategoryRepository $fileCategoryRepository): JsonResponse
    {
        // Get the file according the id
        $file = $this->fileRepository->findOneBy(["id" => $id]);
        if (!$file) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Get the data in the request
        $fileCategoryId = $parametersService->getParameterInRequest("fileCategoryId");
        $title = $parametersService->getParameterInRequest("title");

        // Init the FileUploader
        $fileUploader = new FileUploaderService($this->getPathToFolder($file->getCategory()));

        // Get the old and new category
        $oldFileCategory = $file->getCategory();
        $fileCategory = $fileCategoryRepository->findOneBy(["id" => $fileCategoryId]);

        // Set the file
        $file
            ->setCategory($fileCategory)
            ->setTitle($title);

        // Init the errors
        $validatorService = new ValidationService($this->validator, [$file]);

        if ($fileCategoryId && !$fileCategory) {
            $validatorService->addViolation("file.fileCategory.not_exist", ["id" => $fileCategoryId]);
        }

        // Check the errors
        if (!$validatorService->isValid()) {
            return $this->json($validatorService->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        // Deplace the file
        if ($oldFileCategory !== $file->getCategory()) {
            $fileUploader->moveFile($this->getPathToFolder($file->getCategory()), $file->getName());
        }

        // Manager
        $this->manager->persist($file);
        $this->manager->flush();

        // Response
        return $this->json($file, Response::HTTP_OK, [], ["groups" => "file:read"]);
    }

    /**
     * Remove a file
     *
     * @Route("/api/admin/file/{id}", name="api_admin_file_remove", methods={"DELETE"}, requirements={"id"="[0-9]+"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function removeFile(int $id): JsonResponse
    {
        // Get the file according the id
        $file = $this->fileRepository->findOneBy(["id" => $id]);
        if (!$file) {
            throw new \Exception(sprintf("The id \"%s\" in the query doesn't exist", $id));
        }

        // Remove the file
        $fileUploader = new FileUploaderService($this->getPathToFolder($file->getCategory()));
        $fileUploader->removeFile($file->getName());

        // Manager
        $this->manager->remove($file);
        $this->manager->flush();

        // Response
        return $this->json([], Response::HTTP_OK, [], ["groups" => "file:read"]);
    }

    /**
     * Get the path to the folder
     *
     * @param FileCategory|null $fileCategory
     * @return string
     */
    private function getPathToFolder(?FileCategory $fileCategory): string
    {
        return $fileCategory ? ($this->getParameter("uploads_directory") . "/" . $fileCategory->getSlug()) : $this->getParameter("uncategorized_directory");
    }
}
