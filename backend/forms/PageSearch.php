<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Page;

class PageSearch extends Page
{
    public $id;
    public $title;
    public $slug;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'slug', 'content'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Page::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
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

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}