<?php

namespace unit\entities\Shop\Brand;

use Codeception\Test\Unit;
use shop\entities\Meta;
use shop\entities\Shop\Brand;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $name = 'name';
        $slug = 'slug_1';

        $brand = Brand::create($name, $slug,
            $meta = new Meta('meta_title', 'meta_keywords', 'meta_description')
        );

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);

        $this->assertSame($meta, $brand->meta);
    }
}