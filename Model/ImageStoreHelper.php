<?php

namespace Becklyn\RadBundle\Model;

use Becklyn\RadBundle\Entity\IdEntity;
use Becklyn\RadBundle\Helper\ImageHandler;
use Becklyn\RadBundle\Service\SizedPathService;

class ImageStoreHelper
{
    /**
     * The different image sizes
     *
     * @var array
     */
    private $imageSizes;


    /**
     * Sized path service
     *
     * @var SizedPathService
     */
    private $pathService;


    /**
     * @var string
     */
    private $imageType;


    /**
     * The default image quality
     *
     * @var int
     */
    private $defaultImageQuality;



    /**
     * Creates a new image store helper
     *
     * @param array $imageSizes
     * @param SizedPathService $pathService
     * @param string $imageType
     * @param int $defaultImageQuality
     *
     * @throws \InvalidArgumentException if an invalid image type is given
     */
    public function __construct (array $imageSizes, SizedPathService $pathService, $imageType = "jpg", $defaultImageQuality = 85)
    {
        if (!ImageHandler::isSupportedImageType($imageType))
        {
            throw new \InvalidArgumentException("Image type not supported: {$imageType}. Supported image types are: " . implode(", ", ImageHandler::getSupportedImageTypes()));
        }

        $this->imageSizes          = $imageSizes;
        $this->pathService         = $pathService;
        $this->imageType           = $imageType;
        $this->defaultImageQuality = $defaultImageQuality;
    }



    /**
     * Stores all image versions
     *
     * @param IdEntity $entity
     * @param string $sourceFilePath
     */
    public function storeImageVersions (IdEntity $entity, $sourceFilePath)
    {
        foreach ($this->imageSizes as $size => $maximumDimensions)
        {
            $image = new ImageHandler($sourceFilePath);
            $this->modifyImage($image, $maximumDimensions);

            // generate the filename
            $destinationFilePath = $this->pathService->getFileSystemPath($entity, $size);

            // check for the image directory
            $this->ensureDirectoryExistence(dirname($destinationFilePath));

            // image quality
            $imageQuality = isset($maximumDimensions["quality"]) ? $maximumDimensions["quality"] : $this->defaultImageQuality;

            // store image there
            $saveMethod = "saveAs" . ucwords($this->imageType);
            $image->{$saveMethod}($destinationFilePath, $imageQuality);
        }
    }



    /**
     * Removes all image versions
     *
     * @param IdEntity $entity
     */
    public function removeImageVersions (IdEntity $entity)
    {
        foreach ($this->imageSizes as $size => $maximumDimensions)
        {
            if (is_file($filePath = $this->pathService->getFileSystemPath($entity, $size)))
            {
                @unlink($filePath);
            }
        }
    }



    /**
     * Modifies the image according to the given dimension data
     *
     * @param ImageHandler $image
     * @param array $imageData
     */
    protected function modifyImage (ImageHandler $image, array $imageData)
    {
        if (isset($imageData["maxWidth"], $imageData["maxHeight"]))
        {
            $image->scaleToMaximumDimensions($imageData["maxWidth"], $imageData["maxHeight"]);
        }
        else if (isset($imageData["maxWidth"]))
        {
            $image->scaleToWidth($imageData["maxWidth"]);
        }
        else if (isset($imageData["maxHeight"]))
        {
            $image->scaleToHeight($imageData["maxHeight"]);
        }
        else if (isset($imageData["cropWidth"], $imageData["cropHeight"]))
        {
            $image->cropToSize($imageData["cropWidth"], $imageData["cropHeight"]);
        }
    }



    /**
     * Ensures that the directory exists
     *
     * @param string $dir
     */
    private function ensureDirectoryExistence ($dir)
    {
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
    }
}