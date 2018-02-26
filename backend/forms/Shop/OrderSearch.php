<?php

namespace backend\forms\Shop;

use shop\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Order\Order;

/**
 * OrderSearch represents the model behind the search form about `shop\entities\Shop\Order\Order`.
 */
class OrderSearch extends Model
{
    public $id;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
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
        ]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        return OrderHelper::statusList();
    }
}
