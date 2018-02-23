<?php

namespace backend\forms\Shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\DeliveryMethod;

/**
 * DeliverySearch represents the model behind the search form about `shop\entities\Shop\DeliveryMethod`.
 */
class DeliveryMethodSearch extends Model
{
    public $id;
    public $min_weight;
    public $max_weight;
    public $sort;
    public $cost;
    public $name;

    public function rules(): array
    {
        return [
            [['id', 'min_weight', 'max_weight', 'sort'], 'integer'],
            [['name'], 'safe'],
            [['cost'], 'number'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = DeliveryMethod::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['sort' => SORT_ASC],
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
            'cost' => $this->cost,
        ])
        ->andFilterWhere(['>=','min_weight', $this->min_weight])
        ->andFilterWhere(['>=','max_weight', (float)$this->max_weight]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}