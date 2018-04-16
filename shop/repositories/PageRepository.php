<?php

namespace shop\repositories;

use shop\entities\Page;

class PageRepository
{
    public function get($id): Page
    {
        if(!$page = Page::findOne($id)){
            throw new NotFoundException('Page not found.');
        }
        return $page;
    }

    public function save(Page $page): void
    {
        if(!$page->save()){
            throw new \RuntimeException('Page saving error.');
        }
    }

    public function remove(Page $page): void
    {
        if(!$page->delete()){
            throw new \RuntimeException('Page removing error.');
        }
    }
}