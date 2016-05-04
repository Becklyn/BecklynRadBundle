<?php

namespace Becklyn\RadBundle\DependencyInjection;

use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


/**
 *
 */
class MonitoringCompilerPass implements CompilerPassInterface
{
    /**
     * The service id on which the monitoring handler is registered
     */
    const SERVICE_CONTAINER_ID = "becklyn.monitoring.handler";



    /**
     * {@inheritdoc}
     */
    public function process (ContainerBuilder $container)
    {
        if ($container->hasParameter("becklyn.monitoring.dsn"))
        {
            $monitoringDsn = $container->getParameter("becklyn.monitoring.dsn");

            if (null !== $monitoringDsn)
            {
                // set monitoring DSN in the monitoring handler (for JS integration)
                $container->getDefinition("becklyn_rad.monitoring")->replaceArgument(0, $monitoringDsn);

                // register sentry error handler
                $this->registerSentryLogHandler($monitoringDsn, $container);
            }
        }
    }



    /**
     * Registers the sentry log handler
     *
     * @param string           $monitoringDsn
     * @param ContainerBuilder $container
     */
    private function registerSentryLogHandler ($monitoringDsn, ContainerBuilder $container)
    {
        // generate the service definitions
        $ravenClient = new Definition(\Raven_Client::class, [
            $monitoringDsn
        ]);
        $ravenHandler = new Definition(RavenHandler::class, [
            $ravenClient,
            Logger::WARNING
        ]);
        $fingersCrossedHandler = new Definition(FingersCrossedHandler::class, [
            $ravenHandler,
            Logger::WARNING
        ]);

        $fingersCrossedHandler->setPublic(false);

        // register service in the container and obtain reference
        $container->setDefinition(self::SERVICE_CONTAINER_ID, $fingersCrossedHandler);
        $monitoringReference = new Reference(self::SERVICE_CONTAINER_ID);

        // push reference as handler to every logger in the system
        foreach ($container->getDefinitions() as $key => $definition)
        {
            if (0 === strpos($key, "monolog.logger."))
            {
                $definition->addMethodCall("pushHandler", [$monitoringReference]);
            }
        }
    }
}
