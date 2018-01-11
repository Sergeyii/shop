<?php

namespace forms\manage\Shop\Product;

use yii\base\Model;
use yii\web\UploadedFile;

class ProductImportForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            ['file', 'file', 'extensions' => 'csv'],
        ];
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate()){
            $this->file = UploadedFile::getInstance($this, 'file');

            return true;
        }

        return false;
    }
}