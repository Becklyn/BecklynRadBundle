<?php

namespace Becklyn\RadBundle\Service;

use Becklyn\RadBundle\Entity\IdEntity;

/**
 * Implements a default path service, which eases the default use case of a file path handler
 *
 * @package Becklyn\RadBundle\Service
 */
abstract class DefaultPathService extends AbstractPathService
{
    /**
     * Returns the web server path of the file
     *
     * @param IdEntity $entity
     * @param bool $addCacheFlag if true, adds a cache flag of the last modification (if the file exists)
     *
     * @return string
     */
    public function getWebServerPath (IdEntity $entity, $addCacheFlag = true)
    {
        $flag = "";

        if ($addCacheFlag && $this->hasFile($entity))
        {
            $flag = "?v=" . filemtime($this->getFileSystemPath($entity));
        }

        return "{$this->webServerRoot()}{$this->getStoragePath()}{$this->getFilename($entity)}{$flag}";
    }



    /**
     * Returns the file system path of the file
     *
     * @param IdEntity $entity
     *
     * @return string
     */
    public function getFileSystemPath (IdEntity $entity)
    {
        return "{$this->fileSystemRoot()}{$this->getStoragePath()}{$this->getFilename($entity)}";
    }



    /**
     * Returns whether the file exists
     *
     * @param IdEntity $entity
     *
     * @return bool
     */
    public function hasFile (IdEntity $entity)
    {
        if (is_null($entity->getId()))
        {
            return false;
        }

        return is_file($this->getFileSystemPath($entity));
    }



    /**
     * Returns the image HTML properties
     *
     * @param IdEntity $entity
     *
     * @return string|null
     */
    public function getImageProperties (IdEntity $entity)
    {
        $imageSize = @getimagesize($this->getFileSystemPath($entity));

        if (!is_array($imageSize))
        {
            return null;
        }

        return $imageSize[3];
    }



    /**
     * Returns the path to the storage
     *
     * @return string
     */
    abstract protected function getStoragePath ();



    /**
     * Returns the file name for the entity
     *
     * @param IdEntity $entity
     *
     * @return string
     */
    abstract protected function getFilename (IdEntity $entity);
}