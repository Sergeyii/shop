<?php

namespace shop\entities\Site;

use shop\entities\Site\queries\ManufacturerQuery;
use \yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "manufacturers".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $status
 * @property integer $sort
 * @property string $file
 * @mixin ImageUploadBehavior
 */
class Manufacturer extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    public static function tableName()
    {
        return 'manufacturers';
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/manufacturers/[[attribute_id]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/manufacturers/[[attribute_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/origin/manufacturers/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/origin/manufacturers/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'index_page' => ['width' => 300, 'height' => 0],
                ],
            ],
        ];
    }

    public static function create($title, $slug, $description, $sort, $file): self
    {
        $model = new static();
        $model->title = $title;
        $model->slug = $slug;
        $model->description = $description;
        $model->sort = $sort;
        $model->file = $file;
        return $model;
    }

    public function edit($title, $slug, $description, $sort, $file): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->sort = $sort;
        $this->file = $file;
    }

    public function removePhoto(): void
    {
        if($this->isNewRecord) {
            throw new \DomainException("Can't delete file of new record");
        }

        $this->cleanFiles();
        $this->file = null;
        $this->markAttributeDirty('file');
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDrafted(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        $this->status = self::STATUS_DRAFT;
    }

    public static function find(): ManufacturerQuery
    {
        return new ManufacturerQuery(static::class);
    }
}