<?php

namespace OAGM\BaseBundle\Service;
use OAGM\BaseBundle\Model\IdEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Provides a service for asset path creation
 *
 * @package OAGM\BaseBundle\Service
 */
class SimplePathService extends AbstractPathService
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
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $relativePath the relative path from the web dir
     * @param string $fileExtension the file extension
     * @param string|null $hashPrefix prefix for file name hashing. No hashing is applied to the filename, if $hashPrefix is null
     */
    public function __construct (KernelInterface $kernel, Request $request, $relativePath, $fileExtension, $hashPrefix = null)
    {
        parent::__construct($kernel, $request);

        $this->fileExtension = ltrim($fileExtension, ".");
        $this->hashPrefix    = $hashPrefix;
        $this->relativePath  = "/" . trim($relativePath, "/") . "/";
    }



    /**
     * Returns the webserver path
     *
     * @param IdEntity $entity
     *
     * @return string
     */
    public function getWebserverPath (IdEntity $entity)
    {
        $cache = '';

        if (is_file($filePath = $this->getFileSystemPath($entity)))
        {
            $cache = '?v=' . filemtime($cache);
        }

        return $this->webServerRoot() . $this->relativePath . $this->getFileName($entity) . $cache;
    }



    /**
     * Returns the file system path
     *
     * @param IdEntity $entity
     *
     * @return string
     */
    public function getFileSystemPath (IdEntity $entity)
    {
        return $this->fileSystemRoot() . $this->relativePath . $this->getFileName($entity);
    }



    /**
     * Generates the file name
     *
     * @param IdEntity $entity
     *
     * @return string
     */
    private function getFileName (IdEntity $entity)
    {
        if (is_null($this->hashPrefix))
        {
            return "{$entity->getId()}.{$this->fileExtension}";
        }

        return md5("{$this->hashPrefix}-{$entity->getId()}") . ".{$this->fileExtension}";
    }
}