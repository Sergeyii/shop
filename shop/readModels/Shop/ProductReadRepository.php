<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Product\Product;

class ProductReadRepository
{
    public function getFeatured($limit): array
    {
        return Product::find()->active()->with('mainPhoto')->orderBy(['id' => SORT_DESC])->limit($limit)->all();
    }
}