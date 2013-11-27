<?php

namespace Becklyn\RadBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Abstract base class for path services
 */
abstract class AbstractPathService
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;


    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;



    /**
     * @param KernelInterface $kernel
     * @param Request $request
     */
    public function __construct (KernelInterface $kernel, Request $request)
    {
        $this->kernel = $kernel;
        $this->request = $request;
    }



    /**
     * Returns the file system root path
     *
     * @return string
     */
    protected function fileSystemRoot ()
    {
        return dirname($this->kernel->getRootDir()) . '/web';
    }



    /**
     * Returns the web server root path
     *
     * @return string
     */
    protected function webServerRoot ()
    {
        return $this->request->getBasePath();
    }
}