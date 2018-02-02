<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Value;
use shop\entities\Shop\Tag;
use shop\forms\Shop\Search\SearchForm;
use shop\forms\Shop\Search\ValueForm;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ProductReadRepository
{
    public function getFeatured($limit): array
    {
        return Product::find()->active()->with('mainPhoto')->orderBy(['id' => SORT_DESC])->limit($limit)->all();
    }

    public function getAll(): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        return $this->getProvider($query);
    }

    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');
        $ids = ArrayHelper::merge([$category->id], $category->getLeaves()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');
        return $this->getProvider($query);
    }

    public function getAllByBrand(Brand $brand): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->andWhere(['p.brand_id' => $brand->id]);
        return $this->getProvider($query);
    }

    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        $query->groupBy('p.id');
        return $this->getProvider($query);
    }

    public function find($id): ?Product
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                    'rating' => [
                        'asc' => ['p.rating' => SORT_ASC],
                        'desc' => ['p.rating' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
                'defaultPageSize' => 15,
            ]
        ]);
    }

    public function search(SearchForm $form): DataProviderInterface
    {
        //Найти все товары по выбранным критериям формы
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        if($form->brand){
            //Указан бренд => учитываем бренд
            $query->andWhere(['p.brand_id' => $form->brand]);
        }

        if($form->category){
            if($category = Category::findOne($form->category)){
                //Собираем все id данной категории и её дочерних подкатегорий
                $ids = ArrayHelper::merge([$form->category], $category->getChildren()->select('id')->column());

                $query->joinWith(['categoryAssignments ca'], false);
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
            }else{
                $query->andWhere(['p.id' => 0]);
            }
        }

        if($form->values){
            $productIds = null;

            /* @var ValueForm $value */
            foreach($form->values as $value){
                if($value->isFilled()){
                    $q = Value::find()->andWhere(['characteristic_id' => $value->id]);

                    $q->andFilterWhere(['>=', 'value', $value->from]);
                    $q->andFilterWhere(['<=', 'value', $value->to]);
                    $q->andFilterWhere(['value' => $value->equal]);

                    $foundIds = $q->select('product_id')->column();
                    $productIds = $productIds === null ? $foundIds : array_intersect($productIds, $foundIds);
                }
            }

            if($productIds !== null){
                $query->andWhere(['p.id' => $productIds]);
            }
        }

        if(!empty($form->text)){
            $query->andWhere(['or', ['like', 'p.code', $form->text], ['like', 'p.name', $form->text]]);
        }

        $query->groupBy('p.id');

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                ]
            ],
        ]);

    }

    public function getWishList($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Product::find()
                ->alias('p')->active('p')
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort' => false,
        ]);
    }
}