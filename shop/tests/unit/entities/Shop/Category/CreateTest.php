<?php

namespace unit\entities\Shop\Category;

use Codeception\Test\Unit;
use shop\entities\Meta;
use shop\entities\Shop\Category;

class CreateTest extends Unit
{
    public function testSuccess(): void
    {
        $category = Category::create(
            $name = 'First category',
            $slug = 'first_category_slug',
            $title = 'Название непонятно где выводящееся',
            $description = 'Полное описание',
            $meta = new Meta(
                $meta_title = 'meta_title',
                $meta_keywords = 'meta_keywords',
                $meta_description = 'meta_description'
            )
        );

        //Проверяем правильную заполненность всех полей категории
        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($title, $category->title);
        $this->assertEquals($description, $category->description);

        $this->assertEquals($meta, $category->meta);
    }
}