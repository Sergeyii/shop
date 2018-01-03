<?php

namespace shop\forms\manage;

use shop\entities\Meta;
use yii\base\Model;

class MetaForm extends Model
{
    public $title;
    public $keywords;
    public $description;

    public function __construct(Meta $meta = null, array $config = [])
    {
        if($meta){
            $this->title = $meta->title;
            $this->keywords = $meta->keywords;
            $this->description = $meta->description;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['keywords', 'description'], 'string'],
        ];
    }
}