<?php

namespace Becklyn\RadBundle\Service;

use Becklyn\RadBundle\Entity\IdEntity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Provides a service for asset path creation
 *
 * @package Becklyn\RadBundle\Service
 */
class SimplePathService extends DefaultPathService
{
    /**
     * @var string
     */
    private $fileExtension;


    /**
     * @var string
     */
    private $relativePath;


    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $hashPrefix;


    /**
     *
     *
     * @param KernelInterface $kernel
     * @param RequestStack $requestStack
     * @param string $relativePath the relative path from the web dir
     * @param string $fileExtension the file extension
     * @param string|null $hashPrefix prefix for file name hashing. No hashing is applied to the filename, if $hashPrefix is null
     */
    public function __construct (KernelInterface $kernel, RequestStack $requestStack, $relativePath, $fileExtension, $hashPrefix = null)
    {
        parent::__construct($kernel, $requestStack);

        $this->fileExtension = ltrim($fileExtension, ".");
        $this->hashPrefix    = $hashPrefix;
        $this->relativePath  = "/" . trim($relativePath, "/") . "/";
    }



    /**
     * Returns the path to the storage
     *
     * @return string
     */
    protected function getStoragePath ()
    {
        return $this->relativePath;
    }



    /**
     * Generates the file name
     *
     * @param IdEntity $entity
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getFileName (IdEntity $entity)
    {
        if (is_null($entity->getId()))
        {
            throw new \InvalidArgumentException("Entity id must be set");
        }

        if (is_null($this->hashPrefix))
        {
            return "{$entity->getId()}.{$this->fileExtension}";
        }

        return md5("{$this->hashPrefix}-{$entity->getId()}") . ".{$this->fileExtension}";
    }
}
