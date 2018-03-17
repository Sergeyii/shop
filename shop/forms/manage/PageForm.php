<?php

namespace shop\forms\manage;

use shop\entities\Page;
use shop\forms\CompositeForm;
use yii\helpers\ArrayHelper;

class PageForm extends CompositeForm
{
    public $title;
    public $slug;
    public $content;

    private $_page;

    public function __construct(Page $page=null, array $config = [])
    {
        if($page){
            $this->_page = $page;

            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->meta = new MetaForm($page->meta);
        }else{
            $this->meta = new MetaForm();
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'slug'], 'required'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['slug'], 'unique', 'filter' => $this->_page ? ['<>', 'id' => $this->_page->id] : null],
        ];
    }

    public function parentsList(): array
    {
        return ArrayHelper::map(Page::find()->orderBy('lft')->asArray()->all(), 'id', function(array $page){
            return ($page['depth'] > 1 ? str_repeat('-- ', $page['depth'] - 1).' ' : '').$page['title'];
        });
    }

    protected function internalForms(): array
    {
        return ['meta'];
    }
}