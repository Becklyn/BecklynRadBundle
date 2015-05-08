<?php

namespace Becklyn\RadBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Abstract base class for path services
 */
abstract class AbstractPathService
{
    /**
     * @var KernelInterface
     */
    protected $kernel;


    /**
     * @var RequestStack
     */
    protected $requestStack;



    /**
     * @param KernelInterface $kernel
     * @param RequestStack    $requestStack
     */
    public function __construct (KernelInterface $kernel, RequestStack $requestStack)
    {
        $this->kernel = $kernel;
        $this->requestStack = $requestStack;
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
        return $this->requestStack->getCurrentRequest()->getBasePath();
    }
}
