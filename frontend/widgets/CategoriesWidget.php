<?php

namespace frontend\widgets;

use shop\entities\Shop\Category;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\views\CategoryView;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CategoriesWidget extends Widget
{
    public $categories;
    public $active;

    public function __construct(CategoryReadRepository $categories, array $config = [])
    {
        $this->categories = $categories;
        parent::__construct($config);
    }

    public function run()
    {
        return Html::tag('div', implode(PHP_EOL, array_map(function(CategoryView $categoryView){
            $indent = $categoryView->category->depth > 1 ? str_repeat('&nbsp;&nbsp;&nbsp;', $categoryView->category->depth-1).'- ' : '';

            $isActive = false;
            if($this->active){
                $isActive = $categoryView->category->id == $this->active->id || $this->active->isChildOf($categoryView->category);
            }

            $content = $indent.Html::encode($categoryView->category->name).' '.'('.$categoryView->count.')';

            return Html::a($content, ['category', 'id' => $categoryView->category->id], [
                'class' => 'list-group-item'.($isActive ? ' active' : ''),
            ]);
        }, $this->categories->getTreeWithSubsOf($this->active))), [
            'class' => "list-group",
        ]);
    }
}