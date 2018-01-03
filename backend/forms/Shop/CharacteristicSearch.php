<?php

namespace backend\forms\Shop;

use shop\helpers\CharacteristicHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Shop\Characteristic;

/**
 * CharacteristicSearch represents the model behind the search form about `shop\entities\Shop\Characteristic`.
 */
class CharacteristicSearch extends Characteristic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'required', 'type'], 'integer'],
            ['name', 'safe'],
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
        $query = Characteristic::find();

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
            'type' => $this->type,
            'required' => $this->required,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function typesList(): array
    {
        return CharacteristicHelper::typeList();
    }

    public function requiredList(): array
    {
        return [
            0 => Yii::$app->formatter->asBoolean(0),
            1 => Yii::$app->formatter->asBoolean(1),
        ];
    }
}
