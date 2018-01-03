<?php

namespace shop\forms\manage\Shop;

use shop\helpers\CharacteristicHelper;
use shop\entities\Shop\Characteristic;
use yii\base\Model;

class CharacteristicForm extends Model
{
    public $name;
    public $type;
    public $required;
    public $default;
    public $textVariants;
    public $sort;

    public $_characteristic;

    public function __construct(Characteristic $characteristic = null, array $config = [])
    {
        if($characteristic){
            $this->name = $characteristic->name;
            $this->type = $characteristic->type;
            $this->required = $characteristic->required;
            $this->default = $characteristic->default;
            $this->textVariants = implode(PHP_EOL, $characteristic->variants);
            $this->sort = $characteristic->sort;

            $this->_characteristic = $characteristic;
        }else{
            $this->sort = Characteristic::find()->max('sort') + 1;
        }

        parent::__construct($config);
    }


    public function rules(): array
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'type'], 'string', 'max' => 255],
            ['required', 'boolean'],
            ['default', 'string', 'max' => 255],
            ['textVariants', 'string'],
            ['sort', 'integer'],
            ['name', 'unique', 'targetClass' => Characteristic::class, 'filter' => $this->_characteristic ? ['<>', 'id', $this->_characteristic->id] : null],
        ];
    }

    public function typesList(): array
    {
        return CharacteristicHelper::typeList();
    }

    public function getVariants(): array
    {
        return preg_split('#[\r\n]+#i', $this->textVariants);
    }
}