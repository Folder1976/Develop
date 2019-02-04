<?php echo $header; ?>

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

  <div class="row" id="content">

    <div class="col-md-9 col-md-push-3">
      <h1 class="section-title text-left"><?php echo $heading_title; ?></h1>
      <p>Проверьте правильность заказа и выбранных параметров</p>

      <?php foreach ($products as $product) { ?>
        <div class="row prod-page__prod-info prod-info">
          <div class="col-md-4">

            <?php if ($product['image']) { ?>
              <a class="prod-page__thumb" href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['image']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a>
            <?php } ?>
          </div>

          <div class="col-md-8">
            <div class="cart__product-title"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><div class="cart__remove-product"></div></div>

            <div class="prod-info__row prod-info__row--bdb cart__prod-info">
              <div class="prod-info__col prod-info__col--100">
                <div class="prod-info__col-title">Цена за штуку:</div>
                  <div class="prod-info__price"><?php echo $product['price']; ?></div>
              </div>

              <div class="prod-info__col prod-info__col--100">
                <div class="prod-info__col-title">Количество штук:</div>
                <div class="form-group">
                  <div class="input-count">
                    <button class="input-count__btn js-input-count-minus">-</button>
                    <input type="text" name="quantity" value="<?php echo $product['quantity']; ?>" size="2" id="input-quantity" class="input-count__num">
                    <button class="input-count__btn js-input-count-plus">+</button>
                    
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                  </div>
                </div>
              </div>

              <div class="prod-info__col prod-info__col--100">
                <div class="prod-info__col-title">Итого:</div>
                <?php
                $total_price = floatval($product['price']) * $product['quantity'];
                ?>
                <div class="prod-info__price js-total-price"><?php echo $total_price.' руб.'; ?></div>
              </div>
            </div>


            <div id="specification" class="prod-page__specification">
              <div class="tab-pane" id="tab-specification">
                <table class="table table--prod-specification">
                  <?php if(isset($product['attributes'])){ ?>
                  
                  <?php foreach ($product['attributes'] as $attribute_group) { ?>
                  <thead>
                    <tr>
                      <th><?php echo $attribute_group['name']; ?></th>
                      <th>Значения</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                    <tr>
                      <td><?php echo $attribute['name']; ?></td>
                      <td><?php echo $attribute['text']; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <?php } ?>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>

      <div class="cart__client-info">
        <div class="h3-title">Заполните информацию о себе</div>
        <div id="payment-address">
          <div class="checkout-content row">
            <div class="col-md-12">

              <div class="row">
                <div class="fields-group col-md-4">
                  <?php if($c_logged) {?>
                    <!-- <label for="firstname-ch"> <?=$text_full_name?> :</label><br> -->
                    <input type="text" class="form-control large-field" id="firstname-ch" name="firstname"  value="<?php echo $c_name; ?>" readonly="true" />
                  <?php } else { ?>
                    <!-- <label for="firstname-ch"><span class="required">*</span>   <?=$text_full_name?> :</label><br> -->
                    <input type="text" id="firstname-ch" name="firstname" value="" class="form-control large-field" placeholder="Имя">
                    <span class="error"></span>
                  <?php }?>
                </div>

                <div class="fields-group col-md-4">
                  <!-- <label for="email-ch">   <?=$text_email?>:</label><br> -->
                  <input type="email" id="email-ch" name="email" value="<?php echo $email; ?>"
                  class="form-control large-field" placeholder="E-mail">
                  <span class="error"></span>
                </div>

                <div class="fields-group col-md-4">
                  <!--label for="telephone-ch"><span class="required">*</span>   <?=$text_telephone?>:</label><br-->
                  <input type="text" required="required" id="telephone-ch"  name="telephone"  value="<?php echo $telephone; ?>" class="input--phone form-control large-field" placeholder="Телефон">
                  <span class="error"></span>
                </div>

                <!-- <div class="fields-group col-12 col-md-6">
                    <label for="city-ch">   <?=$text_town?>:</label><br>
                    <input type="text" id="city-ch" name="city" value="<?php echo $city; ?>"
                    class="form-control large-field">
                    <span class="error"></span>
                </div> -->

                <div class="fields-group col-md-12">
                  <input type='hidden' name='delivery-type' value='delivery' />
                  <!-- <label for='address_1'> <?=$text_delivery_type_2?>:</label><br/> -->
                  <input type="text" name="address_1" id="address_1" value="<?php echo $address_1 ?>" class="form-control large-field" placeholder="Адрес доставки">
                  <span class="error"></span>
                </div>

   
                <div class="fields-group col-md-12">
                  <input type="text" name="comment" id="comment" value="<?php echo $comment ?>" class="form-control large-field" placeholder="Комментарий">
                  <span class="error"></span>
                </div>

                <!-- </div>
                    <div class="fields-group col-12 col-md-6">
                     <label for="comment_field">  <?=$text_comment?>:</label>
                     <br>
                     <input type="text" id="comment_field" class="form-control large-field" name="comment"
                     value="<?php echo $comment ?>">
                 </div> -->
              </div>

              <div class="row">
                
                <div class="fields-group col-md-6">
                    <div class="h3-title"><?=$text_payment_method?>:</div>
                      <?php foreach ($payment_methods as $payment_method) { ?>
                      <div class="group-check">
                        <label class="container-radio">

                          <input class="payment_method_value <?php echo $payment_method['code']?> payment_set"
                                     type="radio"
                                     id="payment_select"
                                     name="payment_method"
                                     value='{"title": "<?php echo $payment_method['title'] ?>", "code": "<?php echo $payment_method['code'] ?>"}'
                          ><?php echo $payment_method['title'] ?>

                          <span class="checkmark"></span>

                        </label> &nbsp;&nbsp;
                      </div>
                      <?php } ?>
                    <br>
                </div>

                <div class="fields-group col-md-6">
                    <div class="h3-title"><?=$text_delivery_method?>:</div>
                      <?php foreach ($shippig_methods as $shipping_method) { ?>
                      <div class="group-check">
                           <label class="container-radio">
                           	<input class="delivery-type delivery_set" type="radio" onChange="updateShipping(this)"
                                         id="shipping-method"
                                         name="shipping_method"
                                         value='{"title": "<?php echo $shipping_method['title'] ?>", "code": "<?php echo $shipping_method['value'] ?>", "comment":"", "shipping_method":"<?php echo $shipping_method['value'] ?>", "cost":"<?php echo $shipping_method['cost'] ?>","tax_class_id":""}'
                               ><?php echo $shipping_method['title'] ?>

                               <span class="checkmark"></span>

                           </label> &nbsp;&nbsp;
                      </div>
                      <?php } ?>
                    <br>
                </div>

              </div>

                <!--div class="fields-group col-md-6">
                  <div class="h3-title"><?=$text_payment_method?></div>
                  <select name="payment_method" id="payment_select" class="form-control <?php echo $payment_method['code']?>">
                  <?php foreach ($payment_methods as $payment_method) { ?>
                    <option value='{"title": "<?php echo $payment_method['title'] ?>", "code": "<?php echo $payment_method['code'] ?>"}'><?php echo $payment_method['title'] ?></option>
                  <?php } ?>
                  </select>
                </div>

                <div class="fields-group col-md-6">
                  <div class="h3-title"><?=$text_delivery_method?></div>
                  <select name="shipping_method" id="shipping-method" class="form-control delivery-type delivery_set" onChange="updateShipping(this)">
                  <?php foreach ($shippig_methods as $shipping_method) { ?>
                    <option value='{"title": "<?php echo $shipping_method['title'] ?>", "code": "<?php echo $shipping_method['value'] ?>", "comment":"", "shipping_method":"<?php echo $shipping_method['value'] ?>", "cost":"<?php echo $shipping_method['cost'] ?>","tax_class_id":""}'><?php echo $shipping_method['title'] ?></option>
                  <?php } ?>
                  </select>
                  <input type='hidden' name='delivery-type' value='delivery'/>
                </div-->

              <div class="row">
                <div class="fields-group col-md-12 cart__accept-privacy-policy">
                  <input type="checkbox" class="checkbox" name="accept-privacy-policy" id="accept-privacy-policy" checked>
                  <label for="accept-privacy-policy">С <a href="#">политикой конфиденциальности</a> ознакомлен</label>
                </div>
              </div>

            </div>
          </div>
        </div>  <!-- /#payment-address -->
      </div>  <!-- /.cart__client-info -->
      <?php if ($modules) { ?>
      <div class="panel-group cart-modul-panel" id="accordion">
        <?php foreach ($modules as $module) { ?>
        <?php echo $module; ?>
        <?php } ?>
      </div>
      <?php } ?>
      <div class="cart__result cart-result">
      
        <table id='totals' class='table cart__result cart-result'>
            <tbody>
            <?php foreach ($totals as $total) { ?>
            <tr class="subtotal">
                <td class="name subtotal"><strong><?php echo $total['title']; ?>:</strong></td>
                <td class="price cart-result__total"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>

            </tbody>
        </table>
      </div>
      
      <!--div class="cart__result cart-result">
        <div class="cart-result__row">
          <div class="cart-result__col-l">Общая сумма заказа без учета стоимости доставки:</div>
          <div class="cart-result__col-r cart-result__total">1 589 900.00<span>Р</span></div>
        </div>
        <div class="cart-result__row">
          <div class="cart-result__col-l clearfix">
            <div class="pull-l-lg">Ввести промо-код: <input type="text" name="promo-code" class="form-control cart-result__promo-code"></div>
            <div class="pull-r-lg">Скидка:</div>
          </div>
          <div class="cart-result__col-r cart-result__total cart-result__total--discount">900.00<span>Р</span></div>
        </div>
        <div class="cart-result__row">
          <div class="cart-result__col-l text-right-lg">ИТОГО:</div>
          <div class="cart-result__col-r cart-result__total"><b>1 589 000.00<span>Р</span></b></div>
        </div>
      </div-->

      <div class="buttons clearfix" style="margin-bottom: 5rem;">
        <div class="pull-l-lg pull-l-md pull-l-sm"><a href="#" class="btn btn--transparent">Очистить корзину</a></div>
        <div class="pull-r-lg pull-r-md pull-r-sm"><div id="ajax-button-confirm" class="btn btn--black"><?=$text_confirm?></div></div>
      </div>







    </div>

    <aside id="column-left" class="col-md-3 col-md-pull-9">
      <?php echo $column_left; ?>
    </aside>
  </div>

</div>





<?php if (false) { ?>
<div class="container">

    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
   <?php echo $column_left; ?>
<?php if ($column_left && $column_right) { ?>
<?php $class = 'col-sm-6'; ?>
<?php } elseif ($column_left || $column_right) { ?>
<?php $class = 'col-lg-9'; ?>
<?php } else { ?>
<?php $class = 'col-sm-12'; ?>
<?php } ?>
<div id="content" class="<?php echo $class; ?>">


    <div class="">
        <div class="checkout checkout-checkout">

            <?=$content_top; ?>
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
            <div class="payment row justify-content-center">
                <div class="col-12 col-md-10 checkout-data">
                    <div class="d-flex align-items-center bg-alert justify-content-center">
                        <p class="alert-cart-text m-0 d-none d-md-block">Пройдите регистрацию, и вы сможете воспользоваться дополнительными услугами
                            Личного кабинета и принять участие в партнерских программах ООО «Торговый Дом ВАТТ».</p>
                        <div class="d-flex align-items-center alert-links-cont">
                            <a class="d-flex login-btn" href="//develop.tdwatt.com/index.php?route=account/login"><i class="fa fa-user" aria-hidden="true"></i>Вход</a>
                            <a href="//develop.tdwatt.com/index.php?route=account/login" class="btn btn-danger btn-callbeck">Регистрация</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-10 checkout-data">
                    <div class="cart-info table-responsive">
                        <h1 style="font-size: 20px;font-weight: 600;" class="my-3">Добавлено в корзину</h1>
                        <table class="table table-bdb">
                            <thead>
                                <tr>
                                    <td class="name t-head">  <?=$text_product?></td>
                                    <td class="quantity t-head"><?=$text_quantity?></td>
                                    <td class="price t-head"><?=$text_price?></td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td class="name d-flex align-items-center">
                                        <a href="<?php echo $product['href']; ?>">
                                            <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" style="width: 170px; margin-right: 15px " />
                                        </a>
                                        <a href="<?php echo $product['href']; ?>" style="margin-right: 10px"><?php echo $product['name'] ?></a>
                                        <div class="p-model">
                                            <?php echo $product['model'] ?>                               
                                        </div>
                                        <div class="cart-option">
                                            <?php foreach ($product['option'] as $option) { ?>
                                            -
                                            <small><?php echo $option['name']; ?>
                                                : <?php echo $option['value']; ?>

                                            </small><br/>
                                            <?php } ?>
                                            <?php if ($product['recurring']): ?>
                                            -
                                            <small><?php echo $text_payment_profile ?>
                                                : <?php echo $product['profile_name'] ?>

                                            </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="quantity"><?php echo $product['quantity'] ?>   </td>
                                    <td class="price"><?php echo $product['price'] ?>   </td>

                                </tr>

                                <?php } ?>
                            </table>
                        <!--table id='totals' class='table mt-4'>
                            <tbody>
                            <?php foreach ($totals as $total) { ?>
                            <tr class="subtotal">
                                <td class="name subtotal"><strong><?php echo $total['title']; ?>:</strong></td>
                                <td class="price"><?php echo $total['text']; ?></td>
                            </tr>
                            <?php } ?>

                            </tbody>
                        </table-->
                    </div>
                    </div>

                    <div class="col-12 col-md-10 payment-data">
         <!--        <div class="t-head">
                        <?=$text_customer?>
                    </div> -->
                  <!--  <?php if(!$c_logged) {?>
                    <div id="login_warning" class='checkout-content note'>
                        <?=$text_notlogged?>
                    </div>
                    <?php } ?> -->
                    <div id="payment-address">
                        <h1 style="font-size: 20px;font-weight: 600;" class="my-3"><?=$heading_title?></h1>
                        <div class="checkout-content row" style="overflow: hidden;">
                            <div class="col-12 col-md-8">
                                <div class="row">
                                   <div class="fields-group col-12 col-md-6">
                                    <?php if($c_logged) {?>
                                    <label for="firstname-ch"> <?=$text_full_name?> :</label><br>
                                    <input type="text" class="form-control large-field" id="firstname-ch" name="firstname"  value="<?php echo $c_name; ?>" readonly="true" />
                                    <?php } else { ?>
                                    <label for="firstname-ch"><span class="required">*</span>   <?=$text_full_name?> :</label><br>
                                    <input type="text" id="firstname-ch" name="firstname" value="" class="form-control large-field">
                                    <span class="error"></span>
                                    <?php }?>
                                </div>
                                <div class="fields-group col-12 col-md-6">
                                    <label for="email-ch">   <?=$text_email?>:</label><br>
                                    <input type="email" id="email-ch" name="email" value="<?php echo $email; ?>"
                                    class="form-control large-field">
                                    <span class="error"></span>
                                </div>

                                <div class="fields-group col-12 col-md-6">
                                    <label for="telephone-ch"><span class="required">*</span>   <?=$text_telephone?>:</label><br>
                                    <input type="text" required="required" id="num-place" name="telephone" value="<?php echo $telephone; ?>" class="form-control large-field" placeholder="+38(___)___-__-__">
                                    <span class="error"></span>
                                </div>

                                <div class="fields-group col-12 col-md-6">
                                    <label for="city-ch"><span class="required">*</span>   <?=$text_town?>:</label><br>
                                    <input type="text" id="city-ch" name="city" value="<?php echo $city; ?>"
                                    class="form-control large-field">
                                    <span class="error"></span>
                                </div>


                                <div class="fields-group col-12 col-md-6">
                                    <input type='hidden' name='delivery-type' value='delivery' />
                                    <label for='address_1'> <?=$text_delivery_type_2?>:</label><br/>
                                    <input type="text" name="address_1" id="address_1" value="<?php echo $address_1 ?>" class="form-control large-field" placeholder="  <?=$text_delivery_placeholder?>">
                                    <span class="error"></span>
                                </div>
                                <div class="fields-group col-12 col-md-6">
                                 <label for="comment_field">  <?=$text_comment?>:</label>
                                 <br>
                                 <input type="text" id="comment_field" class="form-control large-field" name="comment"
                                 value="<?php echo $comment ?>">
                             </div>
                         </div>
                     </div>

                     <div class="fields-group col-12 col-md-4">
                         <div>
                             <label for="payment_select">  <?=$text_payment_method?>:</label><br>
                             <?php foreach ($payment_methods as $payment_method) { ?>
                             <div class="group-check">
                                 <label class="container-radio">

                                 	<input class="payment_method_value <?php echo $payment_method['code']?> payment_set"
                                               type="radio"
                                               id="payment_select"
                                               name="payment_method"
                                               value='{"title": "<?php echo $payment_method['title'] ?>", "code": "<?php echo $payment_method['code'] ?>"}'
                                     ><?php echo $payment_method['title'] ?>

                                     <span class="checkmark"></span>

                                 </label> &nbsp;&nbsp;
                             </div>
                             <?php } ?>


                             <label for="delivery">  <?=$text_delivery_method?>:</label><br>
                             <?php foreach ($shippig_methods as $shipping_method) { ?>
                             <div class="group-check">
                                 <label class="container-radio">
                                 	<input class="delivery-type delivery_set" type="radio" onChange="updateShipping(this)"
                                               id="shipping-method"
                                               name="shipping_method"
                                               value='{"title": "<?php echo $shipping_method['title'] ?>", "code": "<?php echo $shipping_method['value'] ?>", "comment":"", "shipping_method":"<?php echo $shipping_method['value'] ?>", "cost":"<?php echo $shipping_method['cost'] ?>","tax_class_id":""}'
                                     ><?php echo $shipping_method['title'] ?>

                                     <span class="checkmark"></span>

                                 </label> &nbsp;&nbsp;
                             </div>
                             <?php } ?>
                         </div>




                   
                                    </div>
<input type='hidden' name='delivery-type' value='delivery'/>
                                
                                <div class="fields-group col-12 col-md-6" style="">

                                </div>

                                <div class="fields-group col-12">
                                	 <div class="checkout-data">
                             <div class="cart-info table-responsive">
                                   <hr/>

                                 <div id="confirm">
                                     <div class="payment">
                                         <div id="ajax-button-confirm" class=" btn btn-lg btn-success btn-good m-0">
                                             <?=$text_confirm?>
                                         </div>
                                         <a href='/'>
                                             <div id="button-modify" class=" btn btn-lg btn-cancel">
                                                 <a href="<?php echo $cart; ?>" class=" btn btn-lg btn-success btn-good m-0" style="background: #145897;"><?php echo $text_cart; ?></a>
                                             </div>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-xs-12 checkout-subinfo">
                                 <?=$content_bottom?>
                             </div>
                         </div>
                                </div>
                            </div>

                        </div>
                    </div>

            </div>
        </div>
        <?php echo $column_right; ?></div>
    </div>
    <div id="LoginModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><?php echo $text_returning_customer; ?></h3>
                </div>
                <div class="modal-body">

                    <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
                    <form  method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                            <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                            <div class="btn btn-primary submit-login-form" ><?php echo $button_login; ?></div>
                            <div>
                                <a href="<?php echo $register ?>"> <?=$text_register;?></a>
                                <br>
                                <a href="<?php echo $forgotten ?>"> <?=$text_forgotten;?></a>

                            </div>

                        </form>
                        <div class="errors-block"></div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="hiden_payment_info"  style="display:none;">

    </div>
</div>
<?php } ?>



<script type="text/javascript">
    $(document).ready(function() {
        // $("#telephone-ch").mask("+99(999)999-99-99")

        $("#collapse-coupon").collapse("show");
        $("#accordion .panel-heading").hide();
    });
    $(document).on('change', '#telephone-ch', function(){

        var num = $("#telephone-ch").val().replace(/\D+/g,"");

        //console.log(num);
        console.log(num.length);

        $('#telerror').hide();

        if(num.length != 11){
            $('#telerror').show();
        }
        //console.log($(this).val().length);
    });

</script>

<script type="text/javascript"><!--

    function updateShipping(s) {
        shp = JSON.parse(s.value)
        $.ajax({
            url: 'index.php?route=checkout/shipping_method/save',
            type: 'post',
            data: shp,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping-method').addClass('loading');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

            //if (json['redirect']) {
            //    location = json['redirect'];
            //}
            if (json['error']) {
                if (json['error']['warning']) {
                 // Error ylanyrkkaan....
                 alert(json['error']['warning']);
             }
         } else {
            $.ajax({
                url: 'index.php?route=checkout/onepagecheckout/totals',
                type: 'get',
                success: function(json) {
                  
                  console.log(json);
                  
                    $('#totals tbody').remove();
                    $('#totals').append('<tbody></tbody');
                    for (t in json['totals']) {
                        $('#totals tbody').append('<tr class="name subtotal"><td class="name subtotal"><strong>'+json['totals'][t]['title']+'</strong></td><td class="price">'+json['totals'][t]["text"]+'</td></tr>');
                    }
                }
            });
                // Update Totalsi!
            }
            $('#shipping-method').removeClass('loading');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }
        ); //ajax
    }

    $(document).ready(function () {
    
        setTimeout(function(){
            $('.payment_set').first().trigger('click');
            $('.delivery_set').first().trigger('click');
        },1000);
        
    
        $(' #LoginModal .submit-login-form ').on('click', function(){
            $.ajax({
                url: 'index.php?route=checkout/onepagecheckout/AjaxLogin',
                type: 'post',
                data: $('#LoginModal #input-email, #LoginModal #input-password '),
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(json) {
                    console.log(json);
                    if(json.errors!=0){
                     if(typeof json.errors.warning!='undefined' && json.errors.warning!='')
                         $('#LoginModal .errors-block').html(json.errors.warning) ;
                     if(typeof json.errors.errors!='undefined'&& json.errors.errors!='')
                         $('#LoginModal .errors-block').append( '<br>' + json.errors.error ) ;
                 }
                 else if(json.errors==0){
                     $('#firstname-ch').prop('value',json.c_name);
                     $('#city-ch').prop('value',json.city);
                     $('#address_1').prop('value',json.address_1);
                     $('#email-ch').prop('value',json.email);
                     $('#telephone-ch').prop('value',json.telephone);
                     $('#LoginModal').modal('hide');
                     $('#login_warning').html('');
                 }
             },
             error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        }
        ); //ajax
            return false;
        });

        $('#ajax-button-confirm').on('click', function () {

            $.ajax({
                url: 'index.php?route=checkout/onepagecheckout',
                type: 'post',
                data: $('.payment-data input[type=\'text\'], .payment-data input[type=\'email\'], .payment-data input[type=\'tel\'], .payment-data input[type=\'radio\']:checked, .delivery-type input[type=\'radio\']:checked, .checkout-checkout .payment-datainput input[type=\'checkbox\']:checked, select, input'),
                dataType: 'json',
                beforeSend: function () {
                    $('#ajax-button-confirm').addClass('preloader');

                },
                complete: function () {
                    $('#ajax-button-confirm').removeClass('preloader');

                },
                success: function (json) {
                   console.log(json);
//debugger;
                    if (json.error) {
                        if (json['error']['firstname']) {
                            $('#firstname-ch+.error').html(json['error']['firstname']);
                        }


                        if (json['error']['email']) {
                            $('#email-ch+.error').html(json['error']['email']);
                        }

                        if (json['error']['telephone']) {
                            $('#telephone-ch+.error').html(json['error']['telephone']);
                        }

                        if (json['error']['address_1']) {
                            $('#address_1+.error').html(json['error']['address_1']);
                        }

                        if (json['error']['city']) {
                            $('#city-ch+.error').html(json['error']['city']);
                        }
                    }

                    else if(json['cod']) {
                        $.ajax({
                            type: 'get',
                            url: 'index.php?route=extension/payment/cod/confirm',
                            cache: false,
                            beforeSend: function() {
                                $('#ajax-button-confirm').button('loading');
                            },
                            complete: function() {
                                $('#ajax-button-confirm').button('reset');
                            },
                            success: function() {
                                location = 'index.php?route=checkout/success';
                            }
                        });
                    }

                    else if(json['payment']) {
                        $('.hiden_payment_info').html(json['payment']);
                        console.log($('.hiden_payment_info a').attr('href'));
                        location = $('.hiden_payment_info a').attr('href');
                    }
                    else {
                        if (json.credit)
                            credit_confirm('/index.php?route=checkout/part_payment_cart/getResult&from_privat24=true');
                        else
                        true;
                           location = 'index.php?route=checkout/success'

}
},
error: function (xhr, ajaxOptions, thrownError) {
 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
}
});


        });


    });
    //--></script>

    <?php echo $footer; ?>
