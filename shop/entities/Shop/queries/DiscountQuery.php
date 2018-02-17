<?php

namespace shop\entities\Shop\queries;

use yii\db\ActiveQuery;

class DiscountQuery extends ActiveQuery
{
    public function active(): ActiveQuery
    {
        return $this->andWhere(['active' => true]);
    }
}