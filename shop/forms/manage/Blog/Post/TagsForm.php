<?php

namespace shop\forms\manage\Blog\Post;

use shop\entities\Blog\Post\Post;
use yii\base\Model;
use \shop\entities\Blog\Tag;
use yii\helpers\ArrayHelper;

/* @property array $newNames */

class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    public function __construct(Post $post = null, array $config = [])
    {
        if($post){
            $this->fillExisting($post->tagAssignments);
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['existing'], 'each', 'rule' => ['integer']],
            [['textNew'], 'string'],
            [['existing'], 'default', 'value' => []],
        ];
    }

    public function tagsList()
    {
        return ArrayHelper::map(Tag::find()->orderBy('id')->all(), 'id', 'name');
    }

    public function getNewNames(): array
    {
        return array_filter(array_map('trim', explode(',', $this->textNew)));
    }

    public function fillExisting($tagAssignments): void
    {
        $this->existing = ArrayHelper::getColumn($tagAssignments, 'tag_id');
    }
}