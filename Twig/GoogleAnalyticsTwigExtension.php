<?php

namespace Becklyn\RadBundle\Twig;


/**
 *
 */
class GoogleAnalyticsTwigExtension extends AbstractTwigExtension
{
    /**
     * Renders all blocks of code required to initialize Google Analytics tracking
     *
     * @param string $trackingCode
     *
     * @return string
     */
    public function analyticsTracking (string $trackingCode) : string
    {
        if (empty($trackingCode))
        {
            return "";
        }

        $analyticsScript = <<<'EOD'
<script type='text/javascript'>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '%s', 'auto'); ga('set', 'anonymizeIp', true); ga('send', 'pageview');
</script>
EOD;

        return sprintf($analyticsScript, $trackingCode);
    }


    /**
     * Returns all defined functions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction("analyticsTracking", [$this, "analyticsTracking"], ["is_safe" => ["html"]]),
        ];
    }
}
