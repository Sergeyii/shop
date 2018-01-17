<div id="cart" class="btn-group btn-block">
    <button type="button" data-toggle="dropdown" data-loading-text="Loading..."
            class="btn btn-inverse btn-block btn-lg dropdown-toggle"><i class="fa fa-shopping-cart"></i>
        <span id="cart-total">3 item(s) - $319.20</span></button>
    <ul class="dropdown-menu pull-right">
        <li>
            <table class="table table-striped">
                <tr>
                    <td class="text-center"><a
                            href="/index.php?route=product/product&amp;product_id=30"><img
                                src="http://static.shop.dev/cache/products/canon_eos_5d_1-47x47.jpg"
                                alt="Canon EOS 5D" title="Canon EOS 5D" class="img-thumbnail"/></a>
                    </td>
                    <td class="text-left"><a
                            href="/index.php?route=product/product&amp;product_id=30">Canon
                            EOS 5D</a>
                        <br/>
                        -
                        <small>Select Red</small>
                    </td>
                    <td class="text-right">x 2</td>
                    <td class="text-right">$196.00</td>
                    <td class="text-center">
                        <button type="button" onclick="cart.remove('2');" title="Remove"
                                class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><a
                            href="/index.php?route=product/product&amp;product_id=40"><img
                                src="http://static.shop.dev/cache/products/iphone_1-47x47.jpg"
                                alt="iPhone" title="iPhone" class="img-thumbnail"/></a>
                    </td>
                    <td class="text-left"><a
                            href="/index.php?route=product/product&amp;product_id=40">iPhone</a>
                    </td>
                    <td class="text-right">x 1</td>
                    <td class="text-right">$123.20</td>
                    <td class="text-center">
                        <button type="button" onclick="cart.remove('1');" title="Remove"
                                class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            </table>
        </li>
        <li>
            <div>
                <table class="table table-bordered">
                    <tr>
                        <td class="text-right"><strong>Sub-Total</strong></td>
                        <td class="text-right">$261.00</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                        <td class="text-right">$6.00</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>VAT (20%)</strong></td>
                        <td class="text-right">$52.20</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">$319.20</td>
                    </tr>
                </table>
                <p class="text-right"><a
                        href="/index.php?route=checkout/cart"><strong><i
                                class="fa fa-shopping-cart"></i> View Cart</strong></a>&nbsp;&nbsp;&nbsp;<a
                        href="/index.php?route=checkout/checkout"><strong><i
                                class="fa fa-share"></i> Checkout</strong></a></p>
            </div>
        </li>
    </ul>
</div>