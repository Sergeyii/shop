<?php

namespace shop\readModels\Site;

use shop\entities\Site\Manufacturer;

class ManufacturerReadRepository
{
    public function getAll($limit)
    {
        return Manufacturer::find()->active()->limit($limit)->all();
    }
}