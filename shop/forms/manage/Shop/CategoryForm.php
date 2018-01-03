<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\Category;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidator;
use yii\helpers\ArrayHelper;

/**
 * @property Meta $meta
 * @property Category $_category
 */
class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;

    public $_category;

    protected function internalForms(): array
    {
        return ['meta'];
    }

    public function __construct(Category $category = null, array $config = [])
    {
        if($category){
            $this->_category = $category;
            $this->meta = new MetaForm($this->_category->meta);

            $this->name = $this->_category->name;
            $this->slug = $this->_category->slug;
            $this->title = $this->_category->title;
            $this->description = $this->_category->parent ? $this->_category->parent->id : null;
        }else{
            $this->meta = new MetaForm();
        }

        parent::__construct($config);
    }

    public function parentCategoriesList()
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->all(), 'id', function(array $category){
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1).' ' : '').$category['name'];
        });
    }

    public function rules()
    {
        return [
            [['parentId'], 'integer'],
            [['name', 'slug', 'title'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['description', 'string'],
            //['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
        ];
    }
}