<?php

namespace shop\forms\manage\Blog;

use shop\entities\Blog\Tag;
use shop\validators\SlugValidator;
use yii\base\Model;

class TagForm extends Model
{
    public $name;
    public $slug;

    private $_tag;

    public function __construct(Tag $tag=null, array $config = [])
    {
        if($tag){
            $this->_tag = $tag;
           $this->name = $tag->name;
           $this->slug = $tag->slug;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique', 'targetClass' => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id]: null],
        ];
    }
}