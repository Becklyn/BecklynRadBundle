<?php

namespace OAGM\BaseBundle\Service;

class UtilsTwigExtension extends AbstractTwigExtension
{
    /**
     * Renders a date as a HTML5 <time> tag
     *
     * @param \DateTime $dateTime      the date to render
     * @param string $displayFormat    the date format string, with which the visible datetime text is displayed
     * @param string $emptyContent     is displayed when the date is null (should be HTML escaped)
     *
     * @return string
     */
    public function renderDateTime (\DateTime $dateTime = null, $displayFormat = "d.m.Y H:i", $emptyContent = "&ndash;")
    {
        if (is_null($dateTime))
        {
            return $emptyContent;
        }

        return '<time datetime="' . $dateTime->format("c") . '">' . $dateTime->format($displayFormat) . '</time>';
    }



    /**
     * Returns all defined functions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction("renderDateTime", [$this, "renderDateTime"], ["is_safe" => ["html"]])
        ];
    }
}