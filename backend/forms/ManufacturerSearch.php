<?php

namespace backend\forms;

use shop\helpers\ManufacturerHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Site\Manufacturer;

/**
 * ManufacturerSearch represents the model behind the search form about `shop\entities\Site\Manufacturer`.
 */
class ManufacturerSearch extends Manufacturer
{
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'slug', 'description'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Manufacturer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        return ManufacturerHelper::statusList();
    }
}