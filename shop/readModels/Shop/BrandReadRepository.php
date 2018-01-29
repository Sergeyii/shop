<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Brand;
use yii\helpers\ArrayHelper;

class BrandReadRepository
{
    public function find($id): ?Brand
    {
        return Brand::findOne($id);
    }

    public function brandsList($orderBy='id'): array
    {
        return ArrayHelper::map(Brand::find()->orderBy($orderBy)->asArray()->all(), 'id', 'name');
    }
}