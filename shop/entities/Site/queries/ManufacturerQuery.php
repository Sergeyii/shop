<?php

namespace shop\entities\Site\queries;

use shop\entities\Site\Manufacturer;
use yii\db\ActiveQuery;

class ManufacturerQuery extends ActiveQuery
{
    public function active($alias=null): ActiveQuery
    {
        return $this->andWhere([
            ($alias ? $alias.'.' : '').'status' => Manufacturer::STATUS_ACTIVE
        ]);
    }
}