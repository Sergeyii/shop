<?php

use \frontend\widgets\Shop\FeaturedProductsWidget;
use \frontend\widgets\Blog\LastsPostWidget;
use \frontend\widgets\Site\ManufacturersWidget;

/* @var $this \yii\web\View */
/* @var $content string */

\frontend\assets\OwlCarouselAsset::register($this);
?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

    <div class="row">
        <div id="content" class="col-sm-12">
            <div id="slideshow0" class="owl-carousel" style="opacity: 1;">
                <div class="item">
                    <a href="index.php?route=product/product&amp;path=57&amp;product_id=49"><img
                            src="http://static.shop.dev/cache/banners/iPhone6-1140x380.jpg"
                            alt="iPhone 6" class="img-responsive"/></a>
                </div>
                <div class="item">
                    <img src="http://static.shop.dev/cache/banners/MacBookAir-1140x380.jpg"
                         alt="MacBookAir" class="img-responsive"/>
                </div>
            </div>
            <h3>Featured</h3>
            <?=FeaturedProductsWidget::widget([
                'limit' => 4
            ])?>

            <h3>Last Posts</h3>
            <?=LastsPostWidget::widget([
                'limit' => 4,
            ])?>

            <?=ManufacturersWidget::widget([
                'limit' => 20,
            ])?>

            <?= $content ?>
        </div>
    </div>

<?php $this->registerJs('
$(\'#slideshow0\').owlCarousel({
    items: 1,
    loop: true,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    nav: true,
    navText: [\'<i class="fa fa-chevron-left fa-5x"></i>\', \'<i class="fa fa-chevron-right fa-5x"></i>\'],
    dots: true
});') ?>

<?php $this->registerJs('
$(\'#carousel0\').owlCarousel({
    items: 6,
    loop: true,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    nav: true,
    navText: [\'<i class="fa fa-chevron-left fa-5x"></i>\', \'<i class="fa fa-chevron-right fa-5x"></i>\'],
    dots: true
});') ?>

<?php $this->endContent() ?>