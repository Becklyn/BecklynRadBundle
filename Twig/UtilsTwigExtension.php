<?php

namespace Becklyn\RadBundle\Twig;


use Twig\TwigFunction;

/**
 *
 */
class UtilsTwigExtension extends AbstractTwigExtension
{
    public function getFunctions () : array
    {
        return [
            new TwigFunction("renderDateTime", $this->renderDateTime(...), ["is_safe" => ["html"]]),
        ];
    }


    /**
     * Renders a date as a HTML5 <time> tag
     *
     * @param \DateTime $dateTime      the date to render
     * @param string $displayFormat    the date format string, with which the visible datetime text is displayed
     * @param string $emptyContent     is displayed when the date is null (should be HTML escaped)
     *
     * @return string
     */
    public function renderDateTime (?\DateTime $dateTime = null, $displayFormat = "d.m.Y H:i", $emptyContent = "&ndash;")
    {
        if (is_null($dateTime))
        {
            return $emptyContent;
        }

        return '<time datetime="' . $dateTime->format("c") . '">' . $dateTime->format($displayFormat) . '</time>';
    }
}
