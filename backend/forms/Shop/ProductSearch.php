<?php

namespace backend\forms\Shop;

use shop\entities\Shop\Category;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Product\Product;
use yii\helpers\ArrayHelper;
use shop\helpers\ProductHelper;

/**
 * ProductSearch represents the model behind the search form about `shop\entities\Shop\Product\Product`.
 */
class ProductSearch extends Product
{
    public $status;
    public $quantity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'brand_id', 'status', 'quantity'], 'integer'],
            [['code', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()->with('mainPhoto', 'category');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'status' => $this->status,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function categoriesList()
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(),'id',function(array $category){
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth']-1).' ' : '').$category['name'];
        });
    }

    public function statusList()
    {
        return ProductHelper::statusList();
    }
}
