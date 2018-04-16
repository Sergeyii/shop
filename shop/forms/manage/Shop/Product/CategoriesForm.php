<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Product\Product;
use shop\readModels\Shop\CategoryReadRepository;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoriesForm extends Model
{
    public $main;
    public $others = [];
    public $categories;

    public function __construct(Product $product = null, CategoryReadRepository $categories, array $config = [])
    {
        if($product){
            $this->main = $product->category_id;
            $this->others = ArrayHelper::getColumn($product->categoryAssignments, 'category_id');
        }

        if($categories){
            $this->categories = $categories;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
        ];
    }

    public function categoriesList(): array
    {
        return $this->categories->categoriesList();
    }
}