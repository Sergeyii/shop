<?php

namespace shop\forms;
use shop\forms\manage\Shop\Product\TagsForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class CompositeForm extends Model
{
    abstract protected function internalForms(): array;

    /* @var Model[] */
    private $forms;

    public function load($data, $formName = null): bool
    {
        //Валидация-проверка самой формы
        $success = parent::load($data, $formName);

        //Валидация-проверка вложенных форм
        foreach($this->forms as $name => $form){
            if( is_array($form) ){
                $success = Model::loadMultiple($form, $data, $formName === null ? null : $name) && $success;
            }else{
                $success = $form->load($data, $formName !== '' ? null : $name) && $success;
            }
        }

        return $success;
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        //Разделяем поля для форм
        //1. Для родительской формы
        $parentNames = $attributeNames !== null ? array_filter((array)$attributeNames, 'is_string') : null;
        $success = parent::validate($parentNames, $clearErrors);

        //2. Для вложенной формы
        foreach($this->forms as $name => $form){
            if( is_array($form) ){
                $success = Model::validateMultiple($form) && $success;
            }else{
                $innerNames = ArrayHelper::getValue($attributeNames, $name);
                $success = $form->validate($innerNames, $clearErrors) && $success;
            }
        }

        return $success;
    }

    public function hasErrors($attribute = null): bool
    {
        if($attribute !== null){
            return parent::hasErrors($attribute);
        }
        if(parent::hasErrors($attribute)){
            return true;
        }
        foreach($this->forms as $name => $form){
            if(is_array($form)){
                foreach($form as $i => $item){
                    if($item->hasErrors()){
                        return true;
                    }
                }
            }else{
                if($form->hasErrors()){
                    return true;
                }
            }
        }
        return false;
    }

    public function getFirstErrors(): array
    {
        $errors = parent::getFirstErrors();
        foreach($this->forms as $name => $form){
            if(is_array($form)){
                foreach($form as $i => $item) {
                    foreach($item->getFirstErrors() as $attribute => $error){
                        $errors[$name. '.' . $i . '.' . $attribute] = $error;
                    }
                }
            }else{
                foreach($form->getFirstErrors() as $attribute => $error){
                    $errors[$name. '.' . $attribute] = $error;
                }
            }
        }
        return $errors;
    }
    
    //Получаем форму по ключу-имени
    public function __get($name)
    {
        if( isset($this->forms[$name]) ){
            return $this->forms[$name];
        }

        return parent::__get($name);
    }

    //Запоминаем-добавляем форму по ключу-имени
    public function __set($name, $value): void
    {
        if( in_array($name, $this->internalForms(), true) ){
            $this->forms[$name] = $value;
        }else{
            parent::__set($name, $value);
        }
    }

    public function __isset($name): bool
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }
}