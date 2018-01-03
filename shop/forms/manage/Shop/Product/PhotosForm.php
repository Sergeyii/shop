<?php

namespace shop\forms\manage\Shop\Product;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property UploadedFile[] $files
 */
class PhotosForm extends Model
{
    public $files;

    public function rules()
    {
        return [
            ['files', 'each', 'rule' => ['image']]
        ];
    }


    public function beforeValidate()
    {
        if( parent::beforeValidate() ){
            $this->files = UploadedFile::getInstance($this, 'files');
            return true;
        }

        return false;
    }
}