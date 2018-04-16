<?php

namespace frontend\controllers;

use shop\entities\Blog\Category as BlogCategory;
use shop\entities\Blog\Post\Post;
use shop\entities\Shop\Category as ShopCategory;
use shop\entities\Shop\Product\Product;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Blog\CategoryReadRepository as BlogCategoryReadRepository;
use shop\readModels\Blog\PostReadRepository;
use Yii;
use shop\entities\Page;
use shop\readModels\PageReadRepository;
use shop\readModels\Shop\CategoryReadRepository as ShopCategoryReadRepository;
use shop\services\sitemap\IndexItem;
use shop\services\sitemap\MapItem;
use shop\services\sitemap\Sitemap;
use yii\caching\TagDependency;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/*
//Будет выводиться такая структура
/sitemap.xml - index
/sitemap-pages.xml
/sitemap-blog-categories.xml
/sitemap-blog-posts-index.xml

/sitemap-blog-posts-0.xml
/sitemap-blog-posts-100.xml
/sitemap-blog-posts-200.xml
/sitemap-blog-posts-300.xml

/sitemap-shop-categories.xml
/sitemap-shop-products-index.xml

/sitemap-blog-products-0.xml
/sitemap-blog-products-100.xml
/sitemap-blog-products-200.xml
/sitemap-blog-products-300.xml
*/

class SitemapController extends Controller
{
    const ITEMS_PER_PAGE = 100;

    private $sitemap;
    private $pages;
    private $blogCategories;
    private $posts;
    private $shopCategories;
    private $products;

    public function __construct(
        string $id,
        $module,
        Sitemap $sitemap,
        PageReadRepository $pages,
        BlogCategoryReadRepository $blogCategories,
        PostReadRepository $posts,
        ShopCategoryReadRepository $shopCategories,
        ProductReadRepository $products,
        array $config = []
    )
    {
        $this->sitemap = $sitemap;
        $this->pages = $pages;
        $this->blogCategories = $blogCategories;
        $this->posts = $posts;
        $this->shopCategories = $shopCategories;
        $this->products = $products;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): Response
    {
        return $this->renderSitemap('sitemap-index', function(){
            return $this->sitemap->generateIndex([
                new IndexItem(Url::to(['pages'], true)),
                new IndexItem(Url::to(['blog-categories'], true)),
                new IndexItem(Url::to(['blog-posts-index'], true)),
                new IndexItem(Url::to(['shop-categories'], true)),
                new IndexItem(Url::to(['shop-products-index'], true)),
            ]);
        }, ['sitemapIndex']);
    }

    public function actionPages(): Response
    {
        return $this->renderSitemap('sitemap-pages', function(){
            return $this->sitemap->generateMap(array_map(function(Page $page){
                return new MapItem(
                    Url::to(['/page/view', 'slug' => $page->slug], true),
                    null,
                    MapItem::WEEKLY
                );
            }, $this->pages->getAll()));
        }, ['sitemapPages']);
    }

    public function actionBlogCategories(): Response
    {
        return $this->renderSitemap('sitemap-blog-categories', function () {
            return $this->sitemap->generateMap(array_map(function (BlogCategory $category) {
                return new MapItem(
                    Url::to(['/blog/post/category', 'slug' => $category->slug], true),
                    null,
                    MapItem::WEEKLY
                );
            }, $this->blogCategories->getAll()));
        }, ['sitemapBlogCategories']);
    }

    public function actionBlogPostsIndex(): Response
    {
        return $this->renderSitemap('sitemap-blog-posts-index', function(){
            return $this->sitemap->generateIndex(array_map(function($start){
                    return new IndexItem(Url::to(['blog-posts', 'start' => $start * self::ITEMS_PER_PAGE], true));
                }, range(0, (int)($this->posts->count() / self::ITEMS_PER_PAGE))));
        }, ['blogPosts']);
    }

    public function actionBlogPosts($start=0): Response
    {
        return $this->renderSitemap(['sitemap-blog-posts', $start], function() use($start){
            return $this->sitemap->generateMap(array_map(function(Post $post){
                return new MapItem(
                    Url::to(['/blog/post/post', 'id' => $post->id], true),
                    null,
                    MapItem::DAILY
                );
            }, $this->posts->getAllByRange($start, self::ITEMS_PER_PAGE)));
        }, ['sitemapPages']);
    }

    public function actionShopCategories(): Response
    {
        return $this->renderSitemap('sitemap-blog-categories', function(){
            return $this->sitemap->generateMap(array_map(function(ShopCategory $category){
                return new MapItem(
                    Url::to(['/shop/catalog/category', 'id' => $category->id], true),
                    null,
                    MapItem::WEEKLY
                );
            }, $this->shopCategories->getAll()));
        }, ['categories', 'sitemapShopCategories']);
    }

    public function actionShopProductsIndex(): Response
    {
        return $this->renderSitemap('sitemap-shop-products-index', function(){
            return $this->sitemap->generateIndex(array_map(function($start){
                    return new IndexItem(Url::to(['shop-products', 'start' => $start * self::ITEMS_PER_PAGE], true));
                }, range(0, (int)($this->products->count() / self::ITEMS_PER_PAGE))));
        }, ['products']);
    }

    public function actionShopProducts($start=0): Response
    {
        return $this->renderSitemap(['sitemap-shop-products', $start], function() use($start){
            return $this->sitemap->generateMap(array_map(function(Product $product){
                return new MapItem(
                    Url::to(['/shop/catalog/product', 'id' => $product->id], true),
                    null,
                    MapItem::DAILY
                );
            }, $this->products->getAllByRange($start, self::ITEMS_PER_PAGE)));
        }, ['products']);
    }

    private function renderSitemap($key, callable $callback, $tagDependencyNames): Response
    {
        $xml = Yii::$app->cache->getOrSet($key, $callback, null, new TagDependency(['tags' => $tagDependencyNames]));
        return Yii::$app->response->sendContentAsFile($xml, Url::canonical(), [
            'mimeType' => 'application/xml',
            'inline' => true,
        ]);
    }
}