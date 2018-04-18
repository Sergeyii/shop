<?php
/* @var \shop\entities\Site\Manufacturer[] $manufacturers */
?>
<div id="carousel0" class="owl-carousel" data-loop="false">
    <?php foreach($manufacturers as $manufacturer):?>
        <div class="item text-center">
            <img src="<?=$manufacturer->getThumbFileUrl('file', 'index_page')?>" alt="<?=$manufacturer->title?>" class="img-responsive" />
        </div>
    <?php endforeach;?>
</div>