<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
		<?php if($count == 0) { ?>
		<li>
			<a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $oct_home_text; ?>">
			<?php echo $breadcrumb['text']; ?>
			</a>
		</li>
		<?php } elseif($count+1<count($breadcrumbs)) { ?>
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a itemscope itemtype="https://schema.org/Thing" itemprop="item" href="<?php echo $breadcrumb['href']; ?>" itemid="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>">
			<span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
			</a>
			<meta itemprop="position" content="<?php echo $count; ?>" />
		</li>
		<?php } else { ?>
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<span itemscope itemtype="https://schema.org/Thing" itemprop="item" itemid="<?php echo $breadcrumb['href']; ?>">
			<span itemprop="name"><?php echo $breadcrumb['text']; ?></span>
			</span>
			<meta itemprop="position" content="<?php echo $count; ?>" />
		</li>
		<?php } ?>
		<?php } ?>
	</ul>
</div>
<?php echo $content_top; ?>
<div class="container">
	<div class="row top-row">
		<div class="col-sm-12">
			<h1 class="cat-header"><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="row">
		<?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>">
			<?php if ($categories) { ?>
			<div class="subcat-header"><?php echo $oct_choose_subcategory; ?></div>
			<div class="row subcats-row">
				<div id="subcats" class="owl-carousel owl-theme">
					<?php foreach ($categories as $category) { ?>
					<div class="item subcat-box">
						<a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>">
						<img class="img-responsive" src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" />
						<?php echo $category['name']; ?>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<script>
				$('#subcats').owlCarousel({
				 items: 6,
				itemsDesktop : [1199,6],
				itemsDesktopSmall : [979,4],
				itemsTablet : [768,4],
				itemsMobile : [479,2],
				autoPlay: false,
				navigation: true,
				    slideMargin: 10,
				    navigationText: ['<i class="fa fa-angle-left fa-5x" aria-hidden="true"></i>', '<i class="fa fa-angle-right fa-5x" aria-hidden="true"></i>'],
				    stopOnHover:true,
				    smartSpeed: 800,
				    loop: true,
				    pagination: false
				});
			</script>
			<?php } ?>
			<?php if ($products) { ?>
			<div class="row sort-row">
				<div class="col-sm-12">
					<div class="col-md-3 col-sm-6 col-xs-12 compare-box">
						<a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a>
					</div>
					<div class="col-md-2 hidden-sm col-xs-5 text-right">
						<label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
					</div>
					<div class="col-md-3 hidden-sm col-xs-7 text-right select-box">
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
					<div class="col-md-2 hidden-sm col-xs-5 text-right">
						<label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
					</div>
					<div class="col-md-1 hidden-sm col-xs-7 text-right select-box">
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
					<div class="col-md-1 col-sm-6 appearance">
						<div class="btn-group hidden-xs">
							<button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
							<button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="mobile-category-header"></div>
			<div id="res-products">
				<div class="row filter-products">
					<?php foreach ($products as $product) { ?>
					<div class="product-layout product-list col-xs-12">
						<div class="product-thumb<?php echo (isset($product['product_preorder_status']) && $product['product_preorder_status'] != 1 && $product['quantity'] <= 0) ? ' no_quantity' : ''?>">
							<?php if ($product['oct_product_stickers']) { ?>
							<div class="cat-sticker-box">
								<?php foreach ($product['oct_product_stickers'] as $product_sticker) { ?>
								<div style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;">
									<?php echo $product_sticker['text']; ?>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
							<?php if ($product['economy']) { ?>
							<div class="cat-discount">-<?php echo $product['economy']; ?>%</div>
							<?php } ?>
							<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
							<div>
								<div class="caption">
									<div class="h4"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
									<p class="description"><?php echo $product['description']; ?></p>
									<?php if ($product['price']) { ?>
									<p class="price">
										<?php if (!$product['special']) { ?>
										<span class="price-new"><?php echo $product['price']; ?></span>
										<?php } else { ?>
										<span class="price-new"><?php echo $product['special']; ?></span> <br/><span class="price-old"><?php echo $product['price']; ?></span>
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
									<?php if (isset($product['oct_options']) && $product['oct_options']) { ?>
									<?php // << Related Options ?>
				<?php if ( !empty($product['ro_data']) && !empty($product['ro_settings']) ) { ?>
					<?php
						if ( !isset($ro_custom_option_block_counter) ) {
							$ro_custom_option_block_counter = 0;
						} else {
							$ro_custom_option_block_counter++;
						}
						$current_ro_block_id = 'ro-options-'.$product['product_id'].'-'.$ro_custom_option_block_counter; 
							
					?>
					
					<?php if ( empty($ro_custom_selectbox_script_included) ) { ?>
						<script src="catalog/view/theme/<?php echo $product['ro_theme_name']; ?>/js/jquery.selectbox-0.2.min.js" type="text/javascript"></script>
						<style>
							<?php if ( $product['ro_theme_name'] == 'theme725' ) { ?>
								.sbDisabled { padding-left:10px; padding-top:8px; padding-bottom:8px; opacity:0.4; line-height:32px; }
							<?php } else { ?>
								.sbDisabled { padding-left:10px; padding-top:8px; padding-bottom:8px; opacity:0.4; line-height:37px; }
							<?php } ?>
						</style>
						<?php
							$ro_custom_selectbox_script_included = true;
						?>
					<?php } ?>
					
					<script type="text/javascript">
					
						$(document).ready(function(){
							var ro_params = {};
							ro_params['ro_settings'] = <?php echo json_encode($product['ro_settings']); ?>;
							ro_params['ro_data'] = <?php echo json_encode($product['ro_data']); ?>;
							ro_params['ro_theme_name'] = '<?php echo json_encode($product['ro_theme_name']); ?>';
							<?php if ( !empty($product['ros_to_select']) ) { ?>
								ro_params['ros_to_select'] = <?php echo json_encode($product['ros_to_select']); ?>;
							<?php } ?>
							
							<?php if ( $product['ro_theme_name'] == 'themeXXX' || $product['ro_theme_name'] == 'theme725' ) { ?>
								var $container_of_options = $('#<?php echo $current_ro_block_id; ?>').closest('.product-options');
							<?php } ?>
							
							if ( $container_of_options && $container_of_options.length ) {
								
								var ro_event_setAccessibleOptionValues_select_after = function(event, product_option_id) {
									ro_instance.getOptionElement('select[name="'+ro_instance.option_prefix+'['+product_option_id+']"]').selectbox("detach");
									ro_instance.getOptionElement('select[name="'+ro_instance.option_prefix+'['+product_option_id+']"]').selectbox({
										effect: "slide",
										speed: 400
									});
								}
								
								<?php if ( !empty($product['ro_settings']['show_clear_options']) ) { ?>
									<?php if ((int)$product['ro_settings']['show_clear_options'] == 1) { ?>
										$(document).ready( function() {
											$container_of_options.find('.options h3').after('<div class="form-group"><div class="col-sm-12 text-left"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div></div>');
										});
									<?php } else { ?>
										$(document).ready( function() {
											$container_of_options.find('.options .form-group:last').after('<div class="form-group"><div class="col-sm-12 text-left"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div></div>');
										});
									<?php } ?>
								
									var clearOptions = function() { // ro_clear_options
			
										ro_instance.getOptionElement('input[type=radio][name^="'+ro_instance.option_prefix+'"]:checked').each(function(){
											var product_option_id = ro_instance.getProductOptionIdFromName($(this).attr('name'));
											ro_instance.setOptionValue(product_option_id, ''); // compatible also with PIODD
										});
										ro_instance.getOptionElement('select[name^="'+ro_instance.option_prefix+'"]').val('');
										ro_instance.getOptionElement('textarea[name^="'+ro_instance.option_prefix+'"]').val('')
										ro_instance.getOptionElement('input[type=text][name^="'+ro_instance.option_prefix+'"]').val('');
										ro_instance.getOptionElement('input[type=checkbox][name^="'+ro_instance.option_prefix+'"]').prop('checked', false);
										ro_instance.getOptionElement('input[type=hidden][name^="'+ro_instance.option_prefix+'"]').val('')
										if ( typeof(ro_instance.controlAccessToValuesOfAllOptions) == 'function' ) {
											ro_instance.controlAccessToValuesOfAllOptions();
										}
										
										ro_instance.executeFunctionsFromOtherExtensionsOnOptionChange();
										
										return false;
									}
									
									$container_of_options.on('click', '#clear_options', function(e){
										e.preventDefault();
										clearOptions();
									});
								<?php } ?>
								
								var ro_instance = $container_of_options.liveopencart_RelatedOptions(ro_params);
								
								ro_instance.bind('setAccessibleOptionValues_select_after.ro', ro_event_setAccessibleOptionValues_select_after);
								
								ro_instance.initRO();
							}
						});
					</script>
				<?php } ?>
				<?php // >> Related Options ?>
									<div class="cat-options">
										<?php foreach ($product['oct_options'] as $option) { ?>
										<?php if ($option['type'] == 'radio') { ?>
										<div class="form-group">
											<label class="control-label"><?php echo $option['name']; ?></label>
											<br/>
											<?php if ($option['product_option_value']) { ?>
											<?php foreach ($option['product_option_value'] as $product_option_value) { ?>
											<?php if ($product_option_value['image']) { ?>
											<div class="radio">
												<label class="not-selected-img">
												<img src="<?php echo $product_option_value['image']; ?>" alt="<?php echo $product_option_value['name']; ?>" class="img-thumbnail" title="<?php echo $product_option_value['name']; ?>" />
												</label>
											</div>
											<?php } else { ?>
											<div class="radio">
												<label class="not-selected"><?php echo $product_option_value['name']; ?></label>
											</div>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</div>
										<?php } else { ?>
										<div class="form-group size-box">
											<label class="control-label"><?php echo $option['name']; ?></label>
											<br/>
											<?php if ($option['product_option_value']) { ?>
											<?php foreach ($option['product_option_value'] as $product_option_value) { ?>
											<div class="radio">
												<label class="not-selected"><?php echo $product_option_value['name']; ?></label>
											</div>
											<?php } ?>
											<?php } ?>
										</div>
										<?php } ?>
										<?php } ?>
									</div>
									<?php } ?>
									<div class="button-group">
													
				<?php // << Related Options ?>
				<?php if ( !empty($current_ro_block_id) ) { ?>
					<input type="hidden" id="<?php echo $current_ro_block_id; ?>" >
				<?php } ?>
				<?php // >> Related Options ?>
										
										<?php if ($product['quantity'] <= 0) { ?>
										<button class="button-cart" type="button" data-effect="mfp-zoom-out" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>><?php echo $product['product_preorder_text']; ?></button>
										<?php } else { ?>
										<button class="button-cart" type="button" data-effect="mfp-zoom-out" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
										<?php } ?>
										<?php if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) { ?>
										<a data-effect="mfp-zoom-out" onclick="get_oct_popup_product_view('<?php echo $product['product_id']; ?>');" title="<?php echo $button_popup_view; ?>" class="more"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<?php } else { ?>
										<a href="<?php echo $product['href']; ?>" title="<?php echo $button_popup_view; ?>" class="more"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<?php } ?>
										<button class="wishlist" type="button" title="<?php echo $button_wishlist; ?>" data-effect="mfp-zoom-out" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart" aria-hidden="true"></i></button>
										<button class="compare" type="button" title="<?php echo $button_compare; ?>" data-effect="mfp-zoom-out" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');"><i class="fa fa-sliders" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="row pagination-row">
					<div class="col-sm-12 text-right"><?php echo $pagination; ?></div>
				</div>
				<?php } ?>
			</div>
			<?php if (!$categories && !$products) { ?>
			<p><?php echo $text_empty; ?></p>
			<div class="buttons">
				<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
			</div>
			<?php } ?>
		</div>
		<?php echo $column_right; ?>
	</div>
</div>
<?php if (strlen($description) > 15) { ?>
<div class="desc-cat">
	<div class="container">
		<div class="row">
			<?php if (strlen($description) > 700) { ?>
			<div class="col-sm-12 desc-cat-text"><?php echo $description; ?></div>
			<div class="col-sm-12 desc-cat-button">
				<a class="button-more" id="desc-cat-button"><?php echo $oct_show_more; ?></a>
			</div>
			<?php } else { ?>
			<div class="col-sm-12 desc-cat-text2"><?php echo str_replace("<img", "<img class=\"img-responsive\"", $description); ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>