<?php

namespace shop\readModels\Shop;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use shop\entities\Shop\Category;
use shop\readModels\Shop\views\CategoryView;
use yii\helpers\ArrayHelper;

class CategoryReadRepository
{
    private $client;

    public function __construct()
    {
        \Yii::$container->setSingleton(Client::class, function(){
            return ClientBuilder::create()->build();
        });

        $this->client = \Yii::$container->get(Client::class);
    }

    public function getRoot(): Category
    {
        return Category::find()->roots()->one();
    }

    /**
     * @return Category[]
     * */
    public function getAll(): array
    {
        return Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->all();
    }

    public function find($id): ?Category
    {
        return Category::find()->andWhere(['id' => $id])->andWhere(['>', 'depth', 0])->one();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->andWhere(['>', 'depth', 0])->one();
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

        $counts = $this->getProductsCount();

        return array_map(function(Category $category) use($counts){
            $count = ArrayHelper::getValue($counts, $category->id, 0);

            return new CategoryView($category, $count);
        }, $query->all());
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(),'id',function(array $category){
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth']-1).' ' : '').$category['name'];
        });
    }

    public function getProductsCount(): array
    {
        $aggs = $this->client->search([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'group_by_category' => [//Название агрегата по котором мы будем потом доставать результат
                        'terms' => [//Ищем по точному совпадению
                            'field' => 'categories',
                        ],
                    ],
                ],
            ],
        ]);

        $counts = ArrayHelper::map($aggs['aggregations']['group_by_category']['buckets'], 'key', 'doc_count');

        return $counts;
    }
}