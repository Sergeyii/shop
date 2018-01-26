<?php

namespace shop\services;

use PHPThumb\GD;

class WaterMarker
{
    public $width;
    public $height;
    public $path;

    public function __construct($width, $height, $path)
    {
        $this->width = $width;
        $this->height = $height;
        $this->path = $path;
    }

    public function process(GD $thumb): void
    {
        $watermark = new GD(\Yii::getAlias($this->path));
        $source = $watermark->getOldImage();

        $thumb->adaptiveResize($this->width, $this->height);

        $originalSize = $thumb->getCurrentDimensions();
        $watermarkSize = $watermark->getCurrentDimensions();

        $destinationX = $originalSize['width'] - $watermarkSize['width'] - 10;
        $destinationY = $originalSize['height'] - $watermarkSize['height'] - 10;

        $destination = $thumb->getOldImage();

        imagealphablending($source, true);
        imagealphablending($destination, true);

        imagecopy($destination,
            $source,
            $destinationX, $destinationY,
            0, 0,
            $watermarkSize['width'], $watermarkSize['height']
        );

        $thumb->setOldImage($destination);
        $thumb->setWorkingImage($destination);
    }
}