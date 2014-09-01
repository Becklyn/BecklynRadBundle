<?php

namespace Becklyn\RadBundle\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 */
class ImageHandler
{
    const IMAGE_TYPE_JPG = "jpg";
    const IMAGE_TYPE_GIF = "gif";
    const IMAGE_TYPE_PNG = "png";


    /**
     * All supported image types
     *
     * @var array
     */
    private static $supportedImageTypes = [
        ImageHandler::IMAGE_TYPE_JPG,
        ImageHandler::IMAGE_TYPE_GIF,
        ImageHandler::IMAGE_TYPE_PNG
    ];



    /**
     * The resource of the image
     * @var resource
     */
    private $resource;



    /**
     * Loads a new image object
     *
     * @param $resource
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($resource)
    {
        if (!is_resource($resource))
        {
            throw new \InvalidArgumentException('You must provide a resource');
        }

        $this->resource = $resource;
        $this->prepareImageResource();
    }



    /**
     * Prepares the image resource
     */
    private function prepareImageResource ()
    {
        imagealphablending($this->resource, false);
        imagesavealpha($this->resource, true);
    }



    /**
     * Loads a new image object from a file
     *
     * @static
     * @param $filePath
     * @return ImageHandler
     */
    public static function loadFromFileImage ($filePath)
    {
        return new ImageHandler(imagecreatefromstring(file_get_contents($filePath)));
    }



    /**
     * Loads a new image object from an uploaded file
     *
     * @static
     * @param UploadedFile $upload
     * @return ImageHandler
     */
    public static function loadFromUploadedFile (UploadedFile $upload)
    {
        return self::loadFromFileInfo($upload);
    }



    /**
     * Loads a new image object from an uploaded file
     *
     * @static
     * @param \SplFileInfo $file
     * @return ImageHandler
     */
    public static function loadFromFileInfo (\SplFileInfo $file)
    {
        return self::loadFromFileImage($file->getPathname());
    }



    /**
     * Frees the image
     */
    public function __destruct ()
    {
        imagedestroy($this->resource);
    }



    /**
     * Crops the image to a specific size
     *
     * @param int $width
     * @param int $height
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function cropToSize ($width, $height)
    {
        if (!self::isValidImageDimension($width) || !self::isValidImageDimension($height))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0. (is: ' . $width . ' x ' . $height . ')');
        }

        $widthFactor = $this->getWidth() / $width;
        $heightFactor = $this->getHeight() / $height;

        $scaleFactor = min($widthFactor, $heightFactor);

        $srcWidth = $scaleFactor * $width;
        $srcHeight = $scaleFactor * $height;

        $srcX = floor(($this->getWidth() - $srcWidth) / 2);
        $srcY = floor(($this->getHeight() - $srcHeight) / 2);

        $this->copyImageResampled(
            0, // $dst_x
            0, // $dst_y
            $srcX, // $src_x
            $srcY, // $src_y
            $width, // $dst_w
            $height, // $dst_h
            $srcWidth, // $src_w
            $srcHeight // $src_h
        );
    }



    /**
     * Scales the image to a specific size
     *
     * @param int $width
     * @param int $height
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function scaleToSize ($width, $height)
    {
        if (!self::isValidImageDimension($width) || !self::isValidImageDimension($height))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0. (is: ' . $width . ' x ' . $height . ')');
        }

        $this->copyImageResampled(
            0, // $dst_x
            0, // $dst_y
            0, // $src_x
            0, // $src_y
            $width, // $dst_w
            $height, // $dst_h
            $this->getWidth(), // $src_w
            $this->getHeight() // $src_h
        );
    }



    /**
     * Scales the image to maximum dimensions
     *
     * @param int $width
     * @param int $height
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function scaleToMaximumDimensions ($width, $height)
    {
        if (!self::isValidImageDimension($width) || !self::isValidImageDimension($height))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0.');
        }

        $heightFactor = $this->getHeight() / $height;
        $widthFactor = $this->getWidth() / $width;

        if ($widthFactor < $heightFactor)
        {
            $this->scaleToHeight($height);
        }
        else
        {
            $this->scaleToWidth($width);
        }
    }



    /**
     * Scales the image proportionally to a height
     *
     * @param int $height
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function scaleToHeight ($height)
    {
        if (!self::isValidImageDimension($height))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0.');
        }
        $width = (int) round(($height / $this->getHeight()) * $this->getWidth());
        $this->scaleToSize($width, $height);
    }



    /**
     * Scales the image proportionally to a width
     *
     * @param int $width
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function scaleToWidth ($width)
    {
        if (!self::isValidImageDimension($width))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0.');
        }

        $height = (int) round(($width / $this->getWidth()) * $this->getHeight());
        $this->scaleToSize($width, $height);
    }



    /**
     * Fits the image in the size and fills the background with a specific color
     *
     * @param int $width
     * @param int $height
     * @param Color $backgroundColor
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function fitInSize ($width, $height, Color $backgroundColor)
    {
        if (!self::isValidImageDimension($width) || !self::isValidImageDimension($height))
        {
            throw new \InvalidArgumentException('Width and Height must be integer > 0. (is: ' . $width . ' x ' . $height . ')');
        }

        $this->scaleToMaximumDimensions($width, $height);

        $dstX = floor(($width - $this->getWidth()) / 2);
        $dstY = floor(($height - $this->getHeight()) / 2);

        // prepares the image after generation
        $imagePreparation = function ($newImage) use ($backgroundColor)
        {
            /** @var Color $backgroundColor */
            $color = imagecolorallocate($newImage, $backgroundColor->getRedByte(), $backgroundColor->getGreenByte(), $backgroundColor->getBlueByte());
            imagefill($newImage, 0, 0, $color);
        };

        $this->copyImageResampled(
            $dstX, // $dst_x
            $dstY, // $dst_y
            0, // $src_x
            0, // $src_y
            $this->getWidth(), // $dst_w
            $this->getHeight(), // $dst_h
            $this->getWidth(), // $src_w
            $this->getHeight(), // $src_h
            $imagePreparation
        );
    }


    /**
     * Crops a rectangle out of the image
     *
     * @param int $xLeft
     * @param int $yTop
     * @param int $xRight
     * @param int $yBottom
     * @throws \InvalidArgumentException if the given parameters are no valid dimensions
     * @throws \LogicException if the dimensions itself are not valid (too big, etc..)
     */
    public function cropRectangle ($xLeft, $yTop, $xRight, $yBottom)
    {
        if (!self::isValidImageDimension($xLeft, true) || !self::isValidImageDimension($yTop, true)
                || !self::isValidImageDimension($xRight, true) || !self::isValidImageDimension($yBottom, true))
        {
            throw new \InvalidArgumentException("Invalid image dimensions given");
        }

        if (($xLeft < 0) || ($yTop < 0) || ($xRight > $this->getWidth()) || ($yBottom > $this->getHeight()))
        {
            throw new \LogicException("Image dimensions do not fit the image");
        }

        $width  = $xRight - $xLeft;
        $height = $yBottom - $yTop;

        $this->copyImageResampled(
            0,
            0,
            $xLeft,
            $yTop,
            $width,
            $height,
            $width,
            $height
        );
    }





    /**
     * Copies and resamples the image
     *
     * @param $destX
     * @param $destY
     * @param $srcX
     * @param $srcY
     * @param $destW
     * @param $destH
     * @param $srcW
     * @param $srcH
     * @param callable $imagePreparation
     */
    private function copyImageResampled ($destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH, callable $imagePreparation = null)
    {
        $newImage = imagecreatetruecolor($destW, $destH);

        // preserve alpha channels
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        if (!is_null($imagePreparation))
        {
            $imagePreparation($newImage);
        }

        imagecopyresampled(
            $newImage, // $dst_image,
            $this->resource, // $src_image
            $destX, // $dst_x
            $destY, // $dst_y
            $srcX, // $src_x
            $srcY, // $src_y
            $destW, // $dst_w
            $destH, // $dst_h
            $srcW, // $src_w
            $srcH // $src_h
        );

        $this->resource = $newImage;
    }



    //region Storage methods
    /**
     * Saves the image as jpeg
     *
     * @param string $newFilePath
     * @param int $quality
     * @deprecated Use saveAsJpg() instead.
     *
     * @return void
     */
    public function saveAsJpeg ($newFilePath, $quality = 90)
    {
        $this->saveAsJpg($newFilePath, $quality);
    }



    /**
     * Saves the image as jpeg file
     *
     * @param string $newFilePath
     * @param int $quality
     *
     * @return void
     */
    public function saveAsJpg ($newFilePath, $quality = 90)
    {
        imagejpeg($this->resource, $newFilePath, $quality);
    }



    /**
     * Saves the image as png
     *
     * @param string $newFilePath
     * @param int $quality
     *
     * @return void
     */
    public function saveAsPng ($newFilePath, $quality = 2)
    {
        imagepng($this->resource, $newFilePath, $quality);
    }



    /**
     * Saves the image as gif
     *
     * @param string $newFilePath
     * @param int $quality
     *
     * @return void
     */
    public function saveAsGif ($newFilePath, $quality = 90)
    {
        imagegif($this->resource, $newFilePath, $quality);
    }



    /**
     * Saves the image as WebP
     *
     * @param string $newFilePath
     *
     * @throws \BadFunctionCallException
     */
    public function saveAsWebP ($newFilePath)
    {
        if (!file_exists('imagewebp'))
        {
            throw new \BadFunctionCallException("Storing images as WebP is not supported (you need to use PHP 5.5.)");
        }

        imagewebp($this->resource, $newFilePath);
    }
    //endregion



    //region Validation
    /**
     * Returns, if the size is a valid image dimension
     * @static
     *
     * @param int $size
     * @param bool $includeZero whether 0 should be included
     *
     * @return bool
     */
    public static function isValidImageDimension ($size, $includeZero = false)
    {
        if (!is_int($size) && !is_float($size) && !ctype_digit($size))
        {
            return false;
        }

        if ($includeZero)
        {
            return 0 <= (int) $size;
        }

        return 0 < (int) $size;
    }
    //endregion



    //region Getters for image characteristics
    /**
     * Returns the width of the image
     *
     * @return int
     */
    public function getWidth ()
    {
        return imagesx($this->resource);
    }



    /**
     * Returns the height of the image
     *
     * @return int
     */
    public function getHeight ()
    {
        return imagesy($this->resource);
    }



    /**
     * Returns the original handler
     * @return resource
     */
    public function getResource ()
    {
        return $this->resource;
    }
    //endregion



    //region Image Types
    /**
     * Returns whether the image type is supported
     *
     * @param string $imageType
     *
     * @return array
     */
    public static function isSupportedImageType ($imageType)
    {
        return in_array($imageType, self::$supportedImageTypes, true);
    }



    /**
     * Returns the supported image types
     *
     * @return array
     */
    public static function getSupportedImageTypes ()
    {
        return self::$supportedImageTypes;
    }
    //endregion
}
