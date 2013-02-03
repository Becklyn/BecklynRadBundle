<?php

namespace OAGM\BaseBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Abstract base class for path services
 */
abstract class AbstractPathService extends ContainerAware
{
    /**
     * Returns the file system root path
     *
     * @return string
     */
    protected function fileSystemRoot ()
    {
        /** @var $kernel \Symfony\Component\HttpKernel\Kernel */
        $kernel = $this->container->get('kernel');

        return dirname($kernel->getRootDir()) . '/web';
    }



    /**
     * Returns the web server root path
     *
     * @return string
     */
    protected function webServerRoot ()
    {
        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container->get('request');

        return $request->getBasePath();
    }
}