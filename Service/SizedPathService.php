<?php

namespace Becklyn\RadBundle\Service;

use Becklyn\RadBundle\Entity\IdEntity;

/**
 * Implements a default path service, which eases the default use case of a file path handler
 *
 * @package Becklyn\RadBundle\Service
 */
abstract class SizedPathService extends AbstractPathService
{
    /**
     * Returns the web server path of the file
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @return string
     */
    public function getWebServerPath (IdEntity $entity, $size)
    {
        $flag = "";

        if ($this->hasFile($entity, $size))
        {
            $flag = "?v=" . filemtime($this->getFileSystemPath($entity, $size));
        }

        return "{$this->webServerRoot()}{$this->getStoragePath($size)}{$this->getFilename($entity, $size)}{$flag}";
    }



    /**
     * Returns the file system path of the file
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @return string
     */
    public function getFileSystemPath (IdEntity $entity, $size)
    {
        return "{$this->fileSystemRoot()}{$this->getStoragePath($size)}{$this->getFilename($entity, $size)}";
    }



    /**
     * Returns whether the file exists
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @return bool
     */
    public function hasFile (IdEntity $entity, $size)
    {
        if (is_null($entity->getId()))
        {
            return false;
        }

        return is_file($this->getFileSystemPath($entity, $size));
    }



    /**
     * Returns the image HTML properties
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @return string|null
     */
    public function getImageProperties (IdEntity $entity, $size)
    {
        $imageSize = @getimagesize($this->getFileSystemPath($entity, $size));

        if (!is_array($imageSize))
        {
            return null;
        }

        return $imageSize[3];
    }



    /**
     * Returns the path to the storage
     *
     * @param string $size
     *
     * @return string
     */
    abstract protected function getStoragePath ($size);



    /**
     * Returns the file name for the entity
     *
     * @param IdEntity $entity
     * @param string $size
     *
     * @return string
     */
    abstract protected function getFilename (IdEntity $entity, $size);
}