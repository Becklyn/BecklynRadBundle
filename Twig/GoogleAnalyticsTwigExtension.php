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
var gaProperty = '%s';
var disableStr = 'ga-disable-' + gaProperty;
if (document.cookie.indexOf(disableStr + '=true') > -1) {
  window[disableStr] = true;
}
function gaOptout() {
  document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
  window[disableStr] = true;
}
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', gaProperty, 'auto'); ga('set', 'anonymizeIp', true); ga('send', 'pageview');
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
