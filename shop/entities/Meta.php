<?php

namespace shop\entities;

use yii\base\Model;

class Meta extends Model
{
    public $title;
    public $keywords;
    public $description;

    public function __construct($title, $keywords, $description)
    {
        $this->title = $title;
        $this->keywords = $keywords;
        $this->description = $description;
    }
}