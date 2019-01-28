<?php echo $header; ?>

<?php
$frequently_visited_pages = array(
  array( 'href' => '#', 'name' => 'Гибкая черепица' ),
  array( 'href' => '#', 'name' => 'Композитная черепица' ),
  array( 'href' => '#', 'name' => 'Фальцевая кровля' ),
  array( 'href' => '#', 'name' => 'Керамическая черепица' ),
  array( 'href' => '#', 'name' => 'Цементно-песчаная черепица' ),
);

$anchor_list = array(
  array( 'href' => $_SERVER['REQUEST_URI'].'#description', 'name' => 'Описание' ),
  array( 'href' => $_SERVER['REQUEST_URI'].'#video', 'name' => 'видео' ),
  array( 'href' => '#', 'name' => 'примеры объектов' ),
  array( 'href' => '#', 'name' => 'цена' ),
);

$video = 'https://www.youtube.com/embed/rVDkUBVaevE';
?>

<div class="container">

  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-9">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="row">

    <div class="col-md-9 col-md-push-3">
      <h1 class="section-title text-left"><?php echo $heading_title; ?></h1>

      <?php if ( isset($anchor_list) && count($anchor_list) > 0 ) { ?>
        <div class="row">
          <div class="col-md-12">
            <ul class="anchor-list">
              <li class="filter__mob-btn"><a href="#filter-mob" class="mf-popup" data-effect="mfp-zoom-in">Подобрать товар</a></li>
              <?php foreach ($anchor_list as $link) { ?>
                <li><a href="<?php echo $link['href']; ?>"><?php echo $link['name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      <?php } ?>

    <?php if(isset($style) and $style == 0){ ?>
      <?php if ( isset($categories) and count($categories) > 0 ) { ?>
        <div class="row">
          <div class="col-md-12">
            <h3 class="h3-title">Часто посещаемые страницы</h3>

            <ul class="pages-list">
            <?php foreach ($categories as $page) { ?>
              <li><a href="<?php echo $page['href']; ?>"><?php echo $page['name']; ?></a></li>
            <?php } ?>
            </ul>

          </div>
        </div>
      <?php } // end.frequently_visited_pages ?>

    <?php }else{ ?>

      <?php if ( isset($categories) and count($categories) > 0 ) { ?>
        <div class="product-list">
          <?php foreach ($categories as $category) { ?>
            <div class="product-layout product-layout--cat">
              <div class="product-layout__image product-thumb">
                <a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a>
              </div>
              <div class="product-layout__caption">
                <h4 class="product-layout__title"><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
              </div>
              <div class="product-layout__bottom">
              <?php if ( $category['price'] ) { ?>
                <div class="product-layout__price">(от <?php echo $category['price']; ?>)</div>
              <?php } ?>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>

    <?php } ?>

      <?php if ($products) { ?>
        <div class="row" style="display: none;">
          <div class="col-md-2 col-sm-6 hidden-xs">
            <div class="btn-group btn-group-sm">
              <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
              <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="form-group">
              <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link"><?php echo $text_compare; ?></a>
            </div>
          </div>
          <div class="col-md-4 col-xs-6">
            <div class="form-group input-group input-group-sm">
              <label class="input-group-addon" for="input-sort"><?php echo $text_sort; ?></label>
              <select id="input-sort" class="form-control" onchange="location = this.value;">
                <?php foreach ($sorts as $sorts) { ?>
                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-3 col-xs-6">
            <div class="form-group input-group input-group-sm">
              <label class="input-group-addon" for="input-limit"><?php echo $text_limit; ?></label>
              <select id="input-limit" class="form-control" onchange="location = this.value;">
                <?php foreach ($limits as $limits) { ?>
                <?php if ($limits['value'] == $limit) { ?>
                <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <div class="product-list">
              <?php foreach ($products as $product) { ?>

                <div class="product-layout">
                  <div class="product-layout__image product-thumb">
                    <!-- Product edit link on front * * * Start -->
                    <?php if(isset($token) AND $token){ ?>
                    <div style="position: absolute;border: 1px solid red;padding: 2px;z-index: 999;background-color: #ffe0e0;">
                      <a style="margin: 2px;" href="/admin/index.php?route=catalog/product/edit&product_id=<?php echo $product['product_id']; ?>&token=<?php echo $token; ?>" target="_blank">edit</a>
                    </div>
                    <?php } ?>
                    <!-- Product edit link on front * * * End -->
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
                  </div>
                  <div class="product-layout__caption">
                    <h4 class="product-layout__title"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <?php if ($product['rating']) { ?>
                    <div class="rating">
                      <?php for ($i = 1; $i <= 5; $i++) { ?>
                      <?php if ($product['rating'] < $i) { ?>
                      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } else { ?>
                      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                      <?php } ?>
                      <?php } ?>
                    </div>
                    <?php } ?>

                    <div class="product-layout__attr-list">
                      
                      <?php $show_attributes = array('23', '29'); $i = 1; ?>
                      
                      <?php foreach($product['attributes'] as $attr){ ?>
                      <?php foreach($attr['attribute'] as $attribute){ ?>
                      
                        <?php //Пока не определились какие атрибуты выводить - просто два первых ?>
                        <?php if($i++ < 3){ ?>
                        <?php //if(in_array($attribute['attribute_id'], $show_attributes)){ ?>
                          <div class="product-layout-attr"><span class="product-layout-attr__title"><?php echo $attribute['name']; ?>:</span> <?php echo $attribute['text']; ?></div>
                        <?php } ?>
                      <?php } ?>
                      <?php } ?>
                    </div>

                    <div class="product-layout__stock is-instock"><?php echo $product['stock']; ?></div>

                    <?php if ($product['special']) { ?>
                      <div class="product-layout__special-price">
                        <div class="product-layout__olp-price"><?php echo $product['price']; ?></div>
                        <div class="product-layout__special-percent">-50%</div>
                      </div>
                    <?php } ?>

                  </div>
                  <div class="product-layout__bottom">

                    <?php if ($product['price']) { ?>
                      <div class="product-layout__price"><?php echo $product['price']; ?></div>
                    <?php } ?>
                    <div class="product-layout__button-group">
                      <button type="button" class="btn btn--black btn--dib btn--buy" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $button_cart; ?></button>
                      <button type="button" class="btn btn--transparent btn--dib btn--buy-click">Купить в 1 клик</button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="pagination-wrap">
          <div class=""><?php echo $pagination; ?></div>
          <div class="form-group input-group input-group-sm limit-per-page">
            <!--label for="input-limit"><?php echo $text_limit; ?></label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
              <?php foreach ($limits as $limits) { ?>
              <?php if ($limits['value'] == $limit) { ?>
              <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select-->
          </div>
        </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
        <div class="row">
          <div class="col-md-12">
            <p><?php echo $text_empty; ?></p>
            <div class="buttons">
              <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>
          </div>
        </div>
      <?php } ?>

      <br>
      <hr class="hr--black">
      <br>
      <br>
      <br>



      <?php if ($thumb || $description) { ?>
        <h3 class="h3-title" id="description">Описание</h3>
        <div class="row">
          <?php if ($thumb) { ?>
          <div class="col-sm-3"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
          <?php } ?>
          <?php if (!$thumb && $description) { ?>
            <div class="col-sm-12"><?php echo $description; ?></div>
          <?php } else { ?>
            <div class="col-sm-9"><?php echo $description; ?></div>
          <?php } ?>
        </div>
        <br>
      <?php } ?>


      <?php if (isset($videos)) { ?>
        <h3 class="h3-title" id="video">Видео</h3>
        <div class="row">
          <?php foreach($videos as $video){ ?>
          <div class="col-sm-3"><iframe src="<?php echo $video; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
          <?php } ?>
        </div>
        <br>
      <?php } ?>

      <?php if (isset($images)) { ?>
        <h3 class="h3-title" id="images">Картинки</h3>
        <div class="row">
          <?php foreach($images as $image){ ?>
          <div class="col-sm-3"><img src="<?php echo $image['popup']; ?>" >
          <!-- ПОЛНЫЙ РАЗМЕР img src="<?php echo $image['thumb']; ?>" -->
          </div>
          <?php } ?>
        </div>
        <br>
      <?php } ?>

    </div>

    <aside id="column-left" class="col-md-3 col-md-pull-9">
      <?php echo $column_left; ?>
    </aside>

  </div>

</div>
















<?php if (false) { ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($thumb || $description) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
        <?php if ($description) { ?>
        <div class="col-sm-10"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <hr>
      <?php } ?>
      <?php if ($categories) { ?>
      <h3><?php echo $text_refine; ?></h3>
      <?php if (count($categories) <= 5) { ?>
      <div class="row">
        <div class="col-sm-3">
          <ul>
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <?php } else { ?>
      <div class="row">
        <?php foreach (array_chunk($categories, ceil(count($categories) / 4)) as $categories) { ?>
        <div class="col-sm-3">
          <ul>
            <?php foreach ($categories as $category) { ?>
            <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php } ?>
      <?php if ($products) { ?>
      <div class="row">
        <div class="col-md-2 col-sm-6 hidden-xs">
          <div class="btn-group btn-group-sm">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link"><?php echo $text_compare; ?></a>
          </div>
        </div>
        <div class="col-md-4 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-sort"><?php echo $text_sort; ?></label>
            <select id="input-sort" class="form-control" onchange="location = this.value;">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-limit"><?php echo $text_limit; ?></label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
              <?php foreach ($limits as $limits) { ?>
              <?php if ($limits['value'] == $limit) { ?>
              <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">

      <!-- Product edit link on front * * * Start -->
      <?php if(isset($token) AND $token){ ?>
      <div style="position: absolute;border: 1px solid red;padding: 2px;z-index: 999;background-color: #ffe0e0;">
        <a style="margin: 2px;" href="/admin/index.php?route=catalog/product/edit&product_id=<?php echo $product['product_id']; ?>&token=<?php echo $token; ?>" target="_blank">edit</a>
      </div>
      <?php } ?>
      <!-- Product edit link on front * * * End -->

            
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p><?php echo $product['description']; ?></p>
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div><!--
        --><div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php } ?>






<?php echo $footer; ?>



