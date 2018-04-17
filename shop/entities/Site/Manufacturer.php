<?php

namespace shop\entities\Site;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "manufacturers".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 */
class Manufacturer extends ActiveRecord
{
    public static function tableName()
    {
        return 'manufacturers';
    }

    public static function create($title, $slug, $description): self
    {
        $model = new static();
        $model->title = $title;
        $model->slug = $slug;
        $model->description = $description;
        return $model;
    }

    public function edit($title, $slug, $description): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
    }
}