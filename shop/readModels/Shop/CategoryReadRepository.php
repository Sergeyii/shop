<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Category;
use yii\helpers\ArrayHelper;

class CategoryReadRepository
{
    public function getRoot(): Category
    {
        return Category::find()->roots()->one();
    }

    public function find($id): ?Category
    {
        return Category::find()->andWhere(['id' => $id])->andWhere(['>', 'depth', 0])->one();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->one();
    }

    public function getTreeWithSubsOf(Category $category = null): array
    {
        $query = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft');

        if($category){
            $criteria = ['or', ['depth' => 1]];
            foreach(ArrayHelper::merge([$category], $category->parents) as $item){
                $criteria[] = ['and', ['>', 'lft', $item->lft], ['<', 'rgt', $item->rgt], ['depth' => $item->depth+1]];
            }
            $query->andWhere($criteria);

        }else{
            $query->andWhere(['depth' => 1]);
        }

        return $query->all();
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(),'id',function(array $category){
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth']-1).' ' : '').$category['name'];
        });
    }
}