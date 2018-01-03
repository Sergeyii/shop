<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Tag;
use shop\validators\SlugValidator;
use yii\base\Model;

class TagForm extends Model
{
    public $name;
    public $slug;
    public $_tag;

    public function __construct(Tag $tag=null, array $config = [])
    {
        if($tag){
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            //['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Tag::class, 'filter' => ( $this->_tag ? ['<>', 'id', $this->_tag->id] : null )],
        ];
    }
}