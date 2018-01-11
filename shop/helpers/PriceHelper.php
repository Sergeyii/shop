<?php
/**
 * Created by PhpStorm.
 * User: NextGenThunder
 * Date: 08.01.2018
 * Time: 17:04
 */

namespace shop\helpers;


class PriceHelper
{
    public static function format($price): string
    {
        return number_format($price, 0, '.', ' ');
    }
}