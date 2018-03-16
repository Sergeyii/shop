<?php

namespace frontend\widgets\Blog;

use shop\readModels\Blog\PostReadRepository;
use yii\base\Widget;

class LastsPostWidget extends Widget
{
    public $limit;
    private $repository;

    public function __construct(PostReadRepository $repository, array $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
    }

    public function run(): string
    {
        return $this->render('last-posts', [
            'posts' => $this->repository->getLast($this->limit),
        ]);
    }
}