<?php

namespace Becklyn\RadBundle\Monitoring;

/**
 *
 */
class MonitoringHandler
{
    /**
     * @var null|string
     */
    private $dsn;



    /**
     * @param null|string $monitoringDsn
     */
    public function __construct ($monitoringDsn = null)
    {
        $this->dsn = $monitoringDsn;
    }



    /**
     * Generates the HTML tag for monitoring the application
     *
     * @return string
     */
    public function generateMonitoringHtml ()
    {
        if (null === $this->dsn)
        {
            return null;
        }

        // strip off the secret
        $publicDsn = preg_replace("~:[a-z0-9]+?@~", "@", $this->dsn);

        $html = '<script src="https://cdn.ravenjs.com/3.0.1/raven.min.js"></script>';
        $html .= '<script>Raven.config("' . $publicDsn . '").install();</script>';

        return $html;
    }
}
