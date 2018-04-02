<?php

namespace shop\useCases\manage;

use shop\entities\Meta;
use shop\entities\Page;
use shop\forms\manage\PageForm;
use shop\repositories\PageRepository;

class PageManageService
{
    private $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($id): Page
    {
        return $this->repository->get($id);
    }

    public function create(PageForm $form): Page
    {
        $page = Page::create(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );
        $this->repository->save($page);
        return $page;
    }

    public function edit($id, PageForm $form): void
    {
        $page = $this->repository->get($id);
        $page->edit(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );
        $this->repository->save($page);
    }

    public function remove($id): void
    {
        $page = $this->repository->get($id);
        $this->repository->remove($page);
    }
}