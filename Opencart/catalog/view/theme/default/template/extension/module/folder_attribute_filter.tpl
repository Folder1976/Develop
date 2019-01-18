<div class="mobile-category-two">
<div class="mobile-category-close"><i class="fa fa-times" aria-hidden="true"></i></div>
<form method=GET action="<?php echo $action; ?>">
<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <div class="list-group">
    
		<?php if(isset($filter_manufactures) AND count($filter_manufactures) > 1){ ?>
				<a class="list-group-item"><?php echo $manufacture_title; ?></a>
				<div class="list-group-item">
					<div id="filter-group-manufacture" class="row">
						<?php foreach ($filter_manufactures as $filter) { ?>
						<div class="checkbox col-6">
							<label>
								<?php if (in_array($filter['manufacturer_id'], $ffilter_manufacturer)) { ?>
								<input type="checkbox" name="manufacturer_id[]" value="<?php echo $filter['manufacturer_id']; ?>" checked="checked" />
								<?php echo $filter['name']; ?>
								<?php } else { ?>
								<input type="checkbox" name="manufacturer_id[]" value="<?php echo $filter['manufacturer_id']; ?>" />
								<?php echo $filter['name']; ?>
								<?php } ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
		<?php } ?>
   
	  <?php foreach ($filter_options as $option_id => $option_info) { ?>
    
        <?php if(isset($option_info['values']) AND count($option_info['values']) > 1){ ?>
           
						<a class="list-group-item"><?php echo $option_info['name']; ?></a>
            <div class="list-group-item">
              <div id="filter-option<?php echo $option_info['option_id']; ?>" class="row">
								
                <?php
									$filter1 = array();
									$filter2 = array();
									
									$total = count($option_info['values']);
									$count = 0;
									foreach ($option_info['values'] as $filter) {
										
										if($count++ >= $total / 2){
											$filter2[] = $filter;
										}else{
											$filter1[] = $filter;
										}
										
									} ?>

								<?php for($i=0;$i<($total/2);$i++){ ?>
                <div class="checkbox col-6">
                  <label>
                    <?php if (isset($ofilter[$option_info['option_id']]) AND in_array($filter1[$i]['option_value_id'], $ofilter[$option_info['option_id']])) { ?>
                    <input type="checkbox" name="ofilter[<?php echo $option_info['option_id']; ?>][]" value="<?php echo $filter1[$i]['option_value_id']; ?>" checked="checked" />
                    <?php echo $filter1[$i]['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ofilter[<?php echo $option_info['option_id']; ?>][]" value="<?php echo $filter1[$i]['option_value_id']; ?>" />
                    <?php echo $filter1[$i]['name']; ?>
                    <?php } ?>
									</label>
								</div>
								<div class="checkbox col-6">
										<?php if(isset($filter2[$i])){ ?>
										<label>
											<?php if (isset($ofilter[$option_info['option_id']]) AND in_array($filter2[$i]['option_value_id'], $ofilter[$option_info['option_id']])) { ?>
											<input type="checkbox" name="ofilter[<?php echo $option_info['option_id']; ?>][]" value="<?php echo $filter2[$i]['option_value_id']; ?>" checked="checked" />
											<?php echo $filter2[$i]['name']; ?>
											<?php } else { ?>
											<input type="checkbox" name="ofilter[<?php echo $option_info['option_id']; ?>][]" value="<?php echo $filter2[$i]['option_value_id']; ?>" />
											<?php echo $filter2[$i]['name'] ?>
											<?php } ?>
										</label>
										<?php } ?>
                </div>
                <?php } ?>
              </div>
            </div>
        <?php } ?>
    <?php } ?>
		
		
		
		
   
	  <?php foreach ($filter_attribute_groups as $filter_group_id => $filter_group) { ?>
    
        <?php if(isset($filter_attributes[$filter_group_id]) AND count($filter_attributes[$filter_group_id]) > 1){ ?>
           
					 <?php //sort($filter_attributes[$filter_group_id]); ?>
						
						<a class="list-group-item"><?php echo $filter_group['name']; ?></a>
            <div class="list-group-item">
              <div id="filter-group<?php echo $filter_group['filter_group_id']; ?>" class="row">
								
                <?php
									$filter1 = array();
									$filter2 = array();
									
									$total = count($filter_attributes[$filter_group_id]);
									$count = 0;
									foreach ($filter_attributes[$filter_group_id] as $filter) {
										
										if($count++ >= $total / 2){
											$filter2[] = $filter;
										}else{
											$filter1[] = $filter;
										}
										
									} ?>

								<?php for($i=0;$i<($total/2);$i++){ ?>
                <div class="checkbox col-6">
                  <label>
                    <?php if (isset($ffilter[$filter_group_id]) AND in_array($filter1[$i], $ffilter[$filter_group_id])) { ?>
                    <input type="checkbox" name="ffilter[<?php echo $filter_group_id; ?>][]" value="<?php echo $filter1[$i]; ?>" checked="checked" />
                    <?php echo $filter1[$i]; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ffilter[<?php echo $filter_group_id; ?>][]" value="<?php echo $filter1[$i]; ?>" />
                    <?php echo $filter1[$i]; ?>
                    <?php } ?>
									</label>
								</div>
								<div class="checkbox col-6">
										<?php if(isset($filter2[$i])){ ?>
										<label>
											<?php if (isset($ffilter[$filter_group_id]) AND in_array($filter2[$i], $ffilter[$filter_group_id])) { ?>
											<input type="checkbox" name="ffilter[<?php echo $filter_group_id; ?>][]" value="<?php echo $filter2[$i]; ?>" checked="checked" />
											<?php echo $filter2[$i]; ?>
											<?php } else { ?>
											<input type="checkbox" name="ffilter[<?php echo $filter_group_id; ?>][]" value="<?php echo $filter2[$i]; ?>" />
											<?php echo $filter2[$i] ?>
											<?php } ?>
										</label>
										<?php } ?>
                </div>
                <?php } ?>
              </div>
            </div>
        <?php } ?>
    <?php } ?>
		
		
  </div>
  <div class="panel-footer text-right">
    <button type="submit" id="button-ffilter" class="btn is-primary"><?php echo $button_filter; ?></button>
  </div>
</div>
</form>
<script type="text/javascript"><!--
$('#button-ffilter1111').on('click', function() {
	ffilter = [];

	$('input[name^=\'ffilter\']:checked').each(function(element) {
		ffilter.push(this.value);
	});

	location = '<?php echo $action; ?>&ffilter=' + ffilter.join(',');
});
//--></script>

</div>