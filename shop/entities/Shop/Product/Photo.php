<?php

namespace shop\entities\Shop\Product;

use PHPThumb\GD;
use shop\services\WaterMarker;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 * @mixin ImageUploadBehavior
 * @property GD $watermark
 * */
class Photo extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%shop_photos}}';
    }

    public function isEqualTo($id): bool
    {
       return $this->id == $id;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;

        return $photo;
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/origin/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/origin/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'cart_list' => ['width' => 150, 'height' => 150],
                    'cart_widget_list' => ['width' => 57, 'height' => 57],
                    'catalog_list' => ['width' => 228, 'height' => 228],
                    'catalog_product_main' => ['processor' => [new WaterMarker(750, 1000, '@static/product/watermarks/watermark.jpg'), 'process']],
                    'catalog_product_additional' => ['width' => 66, 'height' => 66],
                    'catalog_origin' => ['processor' => [new WaterMarker(1024, 768, '@static/product/watermarks/watermark.jpg'), 'process']],
                ],
            ],
        ];
    }
}