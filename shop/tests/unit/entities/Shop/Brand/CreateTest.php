<?php

namespace unit\entities\Shop\Brand;

use Codeception\Test\Unit;
use shop\entities\Meta;
use shop\entities\Shop\Brand;

class CreateTest extends Unit
{
    public function testSuccess(){
        $name = 'name';
        $slug = 'slug_1';

        $meta_title = 'meta_title';
        $meta_keywords = 'meta_keywords';
        $meta_description = 'meta_description';

        $brand = Brand::create($name, $slug, new Meta($meta_title, $meta_keywords, $meta_description) );

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);

        $this->assertEquals($meta_title, $brand->meta->title);
        $this->assertEquals($meta_keywords, $brand->meta->keywords);
        $this->assertEquals($meta_description, $brand->meta->description);
    }
}