<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class service that centralizes all the file upload operations
 */
class FileUploader
{

    /**
     * @param string $productsImgDirectory Path to the directory where products images are saves
     * @param string $catsImgDirectory Path to the directory where cats images are saves
     * @param Filesystem $fileSystem Symphony's filesystem service used to remove files from disk
     */
    public function __construct(

        private string $productsImgDirectory,
        private string $catsImgDirectory,
        private Filesystem $fileSystem,
    ) {}

    /**
     * Upload a product image and return the new filename
     * @param UploadedFile $file The uploaded file
     * @return string The new unique filename
     */
    public function uploadProductImg(UploadedFile $file): string
    {
        return $this->upload($file, $this->productsImgDirectory);
    }

    /**
     * Upload a cat image and return the new filename
     * @param UploadedFile $file The uploaded file
     * @return string The new unique filename
     */
    public function uploadCatsImg(UploadedFile $file): string
    {
        return $this->upload($file, $this->catsImgDirectory);
    }

    /**
     * Method that collect an uploaded file, generates a unique filename and saves it to the target directory
     * @param UploadedFile $file The uploaded file object to be processed
     * @param $directory The target directory where to save the file
     * @return string The unique filename generated for the saved file
     */
    public function upload(UploadedFile $file, string $directory): string
    {
        // collect the file name without his extension
        $strBasefileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Transform the file name unique and concat his extension
        $strNewFilename = $strBasefileName . uniqid() . '.' . $file->guessExtension();

        // Déplace le fichier dans le répertoire /public/uploads/pictures
        $file->move($directory, $strNewFilename);

        //return the new file name
        return $strNewFilename;
    }

    /**
     * Method that remove the file (image product) on the disk
     * @param string $filename The name of the file to delete
     * @return void
     */
    public function removeProductImg(string $filename): void
    {
        // remove va supprimer physiquement le fichier sur le disque à un emplacement indiqué
        $this->fileSystem->remove($this->productsImgDirectory . '/' . $filename);
    }

    /**
     * Method that remove the file (cat product) on the disk
     * @param string $filename The name of the file to delete
     * @return void
     */
    public function removeCatImg(string $filename): void
    {
        // remove delete the file on the disk at the right repertory
        $this->fileSystem->remove($this->catsImgDirectory . '/' . $filename);
    }
}
