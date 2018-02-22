<?php

use shop\helpers\PriceHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var \shop\cart\Cart $cart */
$cost = $cart->getCost();
?>
<div id="cart" class="btn-group btn-block">
    <button type="button" data-toggle="dropdown" data-loading-text="Loading..."
            class="btn btn-inverse btn-block btn-lg dropdown-toggle"><i class="fa fa-shopping-cart"></i>
        <span id="cart-total"><?=$cart->getAmount()?> item(s) - $<?=PriceHelper::format($cost->getTotal())?></span></button>
    <ul class="dropdown-menu pull-right">
        <li>
            <table class="table table-striped">
                <?php foreach($cart->getItems() as $item):
                    $product = $item->getProduct();
                    $modification = $item->getModification();
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php if($product->mainPhoto):?>
                                <a href="<?=$product->mainPhoto->getThumbFileUrl('file', 'catalog_list')?>" target="_blank">
                                    <img src="<?=$product->mainPhoto->getThumbFileUrl('file', 'cart_list')?>" alt="<?=$product->name?>" title="<?=$product->name?>" class="img-thumbnail"/>
                                </a>
                            <?php endif;?>
                        </td>
                        <td class="text-left"><a href="<?=Html::encode(Url::to(['/shop/catalog/product', 'id' => $product->id]))?>"><?=$product->name?></a>
                            <?php if($modification):?>
                                <br/>
                                -
                                <small><?=$modification->name?></small>
                            <?php endif;?>
                        </td>
                        <td class="text-right">x <?=$item->getQuantity()?></td>
                        <td class="text-right">$<?=PriceHelper::format($item->getCost())?></td>
                        <td class="text-center">
                            <button type="button" href="<?=Html::encode(Url::to(['/shop/cart/remove', 'id' => $item->getId()]))?>" data-method="post" title="Remove" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
        </li>
        <li>
            <div>
                <table class="table table-bordered">
                    <tr>
                        <td class="text-right"><strong>Sub-Total</strong></td>
                        <td class="text-right">$<?=PriceHelper::format($cost->getOrigin())?></td>
                    </tr>
                    <?php foreach($cost->getDiscounts() as $discount):?>
                        <tr>
                            <td class="text-right"><strong><?=Html::encode($discount->getName())?></strong></td>
                            <td class="text-right">$<?=PriceHelper::format($discount->getValue())?></td>
                        </tr>
                    <?php endforeach;?>
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">$<?=PriceHelper::format($cost->getTotal())?></td>
                    </tr>
                </table>
                <p class="text-right"><a href="<?=Html::encode(Url::to(['shop/cart/index']))?>"><strong><i class="fa fa-shopping-cart"></i> View Cart</strong></a>&nbsp;&nbsp;&nbsp;
                    <a href="<?=Html::encode(Url::to(['/shop/checkout/index']))?>"><strong><i class="fa fa-share"></i> Checkout</strong></a>
                </p>
            </div>
        </li>
    </ul>
</div>