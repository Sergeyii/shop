<?php

namespace frontend\widgets;

use shop\entities\Shop\Category;
use shop\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;
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
        return Html::tag('div', implode(PHP_EOL, array_map(function(Category $category){
            $indent = $category->depth > 1 ? str_repeat('&nbsp;&nbsp;&nbsp;', $category->depth-1).'- ' : '';
            $isActive = $category->id == $this->active->id || $this->active->isChildOf($category);

            $content = $indent.Html::encode($category->name).' '.'('.$category->getChildren()->count().')';

            return Html::a($content, ['category', 'id' => $category->id], [
                'class' => 'list-group-item'.($isActive ? ' active' : ''),
            ]);
        }, $this->categories->getTreeWithSubsOf($this->active))), [
            'class' => "list-group",
        ]);
    }
}