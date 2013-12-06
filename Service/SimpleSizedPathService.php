<?php

namespace Becklyn\RadBundle\Service;

use Becklyn\RadBundle\Entity\IdEntity;
use Becklyn\RadBundle\Helper\ImageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class SimpleSizedPathService extends SizedPathService
{
    /**
     * @var string
     */
    private $imageType;


    /**
     * @var string
     */
    private $relativePath;


    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $hashPrefix;



    /**
     * Constructs a new simple sized path service
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $relativePath the relative path from the web dir
     * @param string $imageType the image type, on of the ImageHandler::IMAGE_TYPE_* constants
     * @param string|null $hashPrefix prefix for file name hashing. No hashing is applied to the filename, if $hashPrefix is null
     *
     * @throws \InvalidArgumentException
     */
    public function __construct (KernelInterface $kernel, Request $request, $relativePath, $imageType, $hashPrefix = null)
    {
        parent::__construct($kernel, $request);

        if (!ImageHandler::isSupportedImageType($imageType))
        {
            throw new \InvalidArgumentException("Image type not supported: {$imageType}. Supported image types are: " . implode(", ", ImageHandler::getSupportedImageTypes()));
        }

        $this->imageType     = $imageType;
        $this->hashPrefix    = $hashPrefix;
        $this->relativePath  = trim($relativePath, "/");
    }



    /**
     * Returns the path to the storage
     *
     * @param string $size
     *
     * @return string
     */
    protected function getStoragePath ($size)
    {
        return "/{$this->relativePath}/{$size}/";
    }



    /**
     * Generates the file name
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getFileName (IdEntity $entity, $size)
    {
        if (is_null($entity->getId()))
        {
            throw new \InvalidArgumentException("Entity id must be set");
        }

        if (is_null($this->hashPrefix))
        {
            return "{$size}-{$entity->getId()}.{$this->imageType}";
        }

        return md5("{$this->hashPrefix}-{$entity->getId()}-{$size}") . ".{$this->imageType}";
    }
}