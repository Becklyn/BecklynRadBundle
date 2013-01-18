<?php

namespace OAGM\BaseBundle\Helper;

/**
 * Date: 13.01.2011
 * Time: 3:21 PM
 */

class Color
{
    /**
     * The red portion
     * @var int
     */
    private $red;


    /**
     * The green portion
     * @var int
     */
    private $green;


    /**
     * The blue portion
     * @var int
     */
    private $blue;


    /**
     * Predefinied color names
     * @var array
     */
    private static $colorNames = array(
        'white' => array('r' => 255, 'g' => 255, 'b' => 255)
    );

    /**
     * Constructs a new Color object
     * @throws \InvalidArgumentException
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function __construct ($red, $green, $blue)
    {
        if (self::isValidColorByte($red) && self::isValidColorByte($green) && self::isValidColorByte($blue))
        {
            $this->red   = $red;
            $this->green = $green;
            $this->blue  = $blue;
        }
        else
        {
            throw new \InvalidArgumentException('RGB values are invalid.');
        }
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Constructors
    // -----------------------------------------------------------------------------------------------------------------
    /**
     * Creates the color from rgb values
     * @static
     * @param int $red range: 0 - 255
     * @param int $green range: 0 - 255
     * @param int $blue range: 0 - 255
     * @return bool|Color
     */
    public static function getColorFromRGB ($red, $green, $blue)
    {
        try {
            $color = new Color($red, $green, $blue);
            return $color;
        }
        catch (\InvalidArgumentException $e)
        {
            return false;
        }
    }



    /**
     * Creates the color from percentage values
     * @static
     * @param float $red
     * @param float $green
     * @param float $blue
     * @return bool|Color
     */
    public static function getColorFromPercentages ($red, $green, $blue)
    {
        if (!self::isValidPercentage($red)
                || !self::isValidPercentage($green)
                || !self::isValidPercentage($blue))
        {
            return false;
        }

        $red   = round(($red / 100) * 255);
        $green = round(($green / 100) * 255);
        $blue  = round(($blue / 100) * 255);

        return new Color($red, $green, $blue);
    }



    /**
     * Creates the color from a hex string
     * @static
     * @param string $hexString
     * @return bool|Color
     */
    public static function getFromHex ($hexString)
    {
        // remove heading #
        if ("#" === $hexString[0])
        {
            $hexString = substr($hexString, 1);
        }

        if ((3 != strlen($hexString)) && (6 != strlen($hexString)))
        {
            return false;
        }

        if (3 == strlen($hexString))
        {
            $red = substr($hexString, 0, 1);
            $red .= $red;

            $green = substr($hexString, 0, 1);
            $green .= $green;

            $blue = substr($hexString, 0, 1);
            $blue .= $blue;
        }

        $red   = base_convert($red, 36, 10);
        $green = base_convert($green, 36, 10);
        $blue  = base_convert($blue, 36, 10);

        return new Color($red, $green, $blue);
    }



    /**
     * Returns the color by name
     * @static
     * @param string $colorName
     * @return bool|Color
     */
    public static function getFromName ($colorName)
    {
        $colorName = strtolower($colorName);

        if (!array_key_exists($colorName, self::$colorNames))
        {
            return false;
        }

        $color = self::$colorNames[$colorName];
        return new Color($color['r'], $color['g'], $color['b']);
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Validation logic
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Returns, if the number is a valid byte
     * @static
     * @param  $color
     * @return bool
     */
    private static function isValidColorByte ($color)
    {
        return (is_int($color) || ctype_digit($color)) && ($color <= 255);
    }



    /**
     * Returns, if the number is a valid percentage
     * @static
     * @param float $percentage
     * @return bool
     */
    private static function isValidPercentage ($percentage)
    {
        if (!is_float($percentage))
        {
            return false;
        }

        return (0 <= $percentage) && ($percentage <= 100);
    }


    // -----------------------------------------------------------------------------------------------------------------
    // Getter
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Returns the red value as byte
     * @return int
     */
    public function getRedByte ()
    {
        return $this->red;
    }



    /**
     * Returns the blue value as byte
     * @return int
     */
    public function getBlueByte ()
    {
        return $this->blue;
    }



    /**
     * Returns the green value as byte
     * @return int
     */
    public function getGreenByte ()
    {
        return $this->green;
    }
}
