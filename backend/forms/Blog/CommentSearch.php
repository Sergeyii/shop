<?php

namespace backend\forms\Blog;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\Blog\Post\Comment;

class CommentSearch extends Model
{
    public $id;
    public $post_id;
    public $text;
    public $active;

    public function rules(): array
    {
        return [
            [['id', 'post_id'], 'integer'],
            [['text'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Comment::find()->with(['post']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => function(Comment $comment){
                return [
                    'post_id' => $comment->post_id,
                    'id' => $comment->id,
                ];
            },
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'post_id' => $this->post_id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }

    public function activeList(): array
    {
        return [
            0 => Yii::$app->formatter->asBoolean(false),
            1 => Yii::$app->formatter->asBoolean(true),
        ];
    }
}