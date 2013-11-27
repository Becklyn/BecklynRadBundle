<?php

namespace Becklyn\RadBundle\Service;

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
     * Pluralizes the text
     *
     * @param int $amount
     * @param string $singleText
     * @param string $multipleText
     *
     * @return string
     */
    public function pluralize ($amount, $singleText, $multipleText = null)
    {
        if (is_null($multipleText))
        {
            $multipleText = "{$singleText}er";
        }

        if (1 === $amount)
        {
            return "{$amount} {$singleText}";
        }

        return "{$amount} {$multipleText}";
    }


    /**
     * Returns all defined functions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction("renderDateTime", [$this, "renderDateTime"], ["is_safe" => ["html"]]),
            new \Twig_SimpleFunction("pluralize", [$this, "pluralize"]),
        ];
    }
}