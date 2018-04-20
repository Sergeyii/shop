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
                    <img src="<?=Yii::getAlias('@static')?>/files/main_page_slider/iphone1.jpg" alt="iPhone 6" class="img-responsive" style="max-height: 340px;"/>
                </div>
                <div class="item">
                    <img src="<?=Yii::getAlias('@static')?>/files/main_page_slider/samsunggalaxy.jpg" alt="Samsung galaxy" class="img-responsive" style="max-height: 340px;"/>
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
    center: true,
    loop: true,
    autoplay:true,
    autoplayTimeout:4000,
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