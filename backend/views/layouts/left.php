<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Пользователи', 'icon' => 'user', 'url' => ['/user'], 'active' => ( $this->context->id == 'user' )],
                    ['label' => 'Shop', 'icon' => 'folder', 'items' =>
                        [
                            ['label' => 'Производители', 'icon' => 'file-o', 'url' => ['/manufacturer'], 'active' => ( $this->context->id == 'manufacturer' )],
                            ['label' => 'Страницы', 'icon' => 'file-o', 'url' => ['/page'], 'active' => ( $this->context->id == 'page' )],
                            ['label' => 'Файлы', 'icon' => 'file-o', 'url' => ['/file'], 'active' => ( $this->context->id == 'file' )],
                            ['label' => 'Brands', 'icon' => 'file-o', 'url' => ['/shop/brand/index'], 'active' => ( $this->context->id == 'shop/brand' ),],
                            ['label' => 'Tags', 'icon' => 'file-o', 'url' => ['/shop/tag/index'], 'active' => ($this->context->id == 'shop/tag')],
                            ['label' => 'Categories', 'icon' => 'file-o', 'url' => ['/shop/category/index'], 'active' => ($this->context->id == 'shop/category')],
                            ['label' => 'Characteristics', 'icon' => 'file-o', 'url' => ['/shop/characteristic/index'], 'active' => ($this->context->id == 'shop/characteristic')],
                            ['label' => 'Products', 'icon' => 'file-o', 'url' => ['/shop/product/index'], 'active' => ($this->context->id == 'shop/product')],
                            ['label' => 'Delivery methods', 'icon' => 'file-o', 'url' => ['/shop/delivery/index'], 'active' => ($this->context->id == 'shop/delivery')],
                            ['label' => 'Orders', 'icon' => 'file-o', 'url' => ['/shop/order/index'], 'active' => ($this->context->id == 'shop/order')],
                            ['label' => 'Blog', 'icon' => 'folder', 'items' => [
                                ['label' => 'Categories', 'icon' => 'file-o', 'url' => ['/Blog/category/index'], 'active' => ($this->context->id == 'Blog/category')],
                                ['label' => 'Tags', 'icon' => 'file-o', 'url' => ['/Blog/tag/index'], 'active' => ($this->context->id == 'Blog/tags')],
                                ['label' => 'Posts', 'icon' => 'file-o', 'url' => ['/Blog/post/index'], 'active' => ($this->context->id == 'Blog/post')],
                                ['label' => 'Comments', 'icon' => 'file-o', 'url' => ['/Blog/comment/index'], 'active' => ($this->context->id == 'Blog/comment')],
                            ]],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
