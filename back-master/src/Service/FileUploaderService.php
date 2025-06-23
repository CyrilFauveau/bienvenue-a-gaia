<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploaderService
{
    /**
     * @var string
     */
    private $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Create a folder
     *
     * @param string $pathToFolder
     * @return void
     */
    public function createFolder(string $pathToFolder): void
    {
        if (!$this->exists($pathToFolder)) {
            mkdir($pathToFolder, 0775, true);
        }
    }

    /**
     * Check if a file or a folder exists
     *
     * @param string $fileName
     * @return bool
     */
    public function exists(string $fileName): bool
    {
        return file_exists($fileName);
    }

    /**
     * Upload a file
     *
     * @param UploadedFile $file
     * @return null|array
     */
    public function uploadFile(UploadedFile $file): ?array
    {
        $this->createFolder($this->targetDirectory);

        $extension = $file->guessClientExtension();
        $fileName = md5(uniqid()) . "." . $extension;

        // Create the file
        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {
            return null;
        }

        // Return the name of the file
        return [$fileName, $extension];
    }

    /**
     * Delete a file
     *
     * @param string $fileName
     * @return void
     */
    public function removeFile(string $fileName): void
    {
        $pathToFile = $this->targetDirectory . '/' . $fileName;
        if ($this->exists($pathToFile)) {
            unlink($pathToFile);
        }
    }

    /**
     * Delete a folder
     *
     * @return void
     */
    public function removeFolder(): void
    {
        if ($this->exists($this->targetDirectory)) {
            rmdir($this->targetDirectory);
        }
    }

    /**
     * Rename a folder
     *
     * @param string $pathToFolder
     * @return void
     */
    public function renameFolder(string $pathToFolder): void
    {
        if ($this->exists($this->targetDirectory)) {
            rename($this->targetDirectory, $pathToFolder);
        }
    }

    /**
     * Move a file
     *
     * @param string $folderName
     * @param string $fileName
     * @return void
     */
    public function moveFile(string $pathToFolder, string $fileName): void
    {
        if ($this->exists($this->targetDirectory)) {
            $this->createFolder($pathToFolder);
            rename($this->targetDirectory . "/" . $fileName, $pathToFolder . "/" . $fileName);
        }
    }
}
