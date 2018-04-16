<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\DeliveryMethod;

class DeliveryMethodReadRepository
{
    /* @return DeliveryMethod[] */
    public function getAll(): array
    {
        return DeliveryMethod::find()->orderBy('sort')->all();
    }
}