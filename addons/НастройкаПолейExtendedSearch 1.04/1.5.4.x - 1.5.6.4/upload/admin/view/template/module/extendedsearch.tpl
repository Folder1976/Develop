<?php echo $header; ?>
<style type="text/css">
.toggle2,.toggle2:after{-webkit-transition:all .2s cubic-bezier(.445,.05,.55,.95)}.label-primary{background-color:#1E91CF;border-color:#1978AB}.sobut{display:inline-block;margin-right:10px;text-decoration:none!important;padding:.4em .9em;font-weight:700;color:#FFF!important;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em}.toggle,.toggle2{font-weight:700;width:120px;height:30px;border-radius:5px;display:inline-block}h2{border-bottom:none!important}td.right{text-align:right}.addpad{padding-top:0;padding-bottom:0}.toggleWrapper{top:50%;overflow:hidden}.toggleWrapper input{position:absolute;left:-99em}.toggle2{position:relative;background:#FFF;transition:all .2s cubic-bezier(.445,.05,.55,.95)}.toggle2:after{position:absolute;line-height:30px;font-size:14px;z-index:2;transition:all .2s cubic-bezier(.445,.05,.55,.95);content:"---";left:50px;color:#CCC}.toggle,.toggle:after,.toggle:before{-webkit-transition:all .2s cubic-bezier(.445,.05,.55,.95)}.toggle{cursor:pointer;position:relative;background:#ccc;transition:all .2s cubic-bezier(.445,.05,.55,.95)}#menu{z-index:3}.toggle:after,.toggle:before{position:absolute;line-height:30px;font-size:14px;z-index:2;transition:all .2s cubic-bezier(.445,.05,.55,.95)}.toggle:before{content:"OFF";left:20px;color:#ccc}.toggle:after{content:"ON";right:20px;color:#fff}.toggle__handler{display:inline-block;position:relative;z-index:1;background:#fff;width:65px;height:24px;border-radius:3px;top:3px;left:3px;-webkit-transition:all .2s cubic-bezier(.445,.05,.55,.95);transition:all .2s cubic-bezier(.445,.05,.55,.95);-webkit-transform:translateX(0);transform:translateX(0)}input:checked+.toggle{background:#66B317}input:checked+.toggle:before{color:#fff}input:checked+.toggle:after{color:#66B317}input:checked+.toggle .toggle__handler{width:54px;-webkit-transform:translateX(60px);transform:translateX(60px);border-color:#fff}
</style>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" />ExtendedSearch 1.04</h1>
    <div class="buttons"><a href="https://opencartforum.com/profile/688391-alexdw/?do=content&type=downloads_file" class="sobut label-primary" target="_blank">Другие дополнения</a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="form">
              <tr>
                <td class="right"><?php echo $entry_status; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_status) { ?>
					<input type="checkbox" id="input-status" name="extendedsearch_status" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-status" name="extendedsearch_status" value="1" />
					<?php } ?>
					<label class="toggle" for="input-status"><span class="toggle__handler"></span></label>
					</div>
				</td>
              </tr>
            </table>

            <table class="form">
              <tr>
				<td colspan="4">
				<h2><?php echo $text_extsearch; ?></h2>
				</td>
              </tr>
              <tr>
                <td class="right"><?php echo $entry_model; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_model) { ?>
					<input type="checkbox" id="input-model" name="extendedsearch_model" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-model" name="extendedsearch_model" value="1" />
					<?php } ?>
					<label class="toggle" for="input-model"><span class="toggle__handler"></span></label>
					</div>
				</td>
                <td></td>
				<td></td>
              </tr>
			  
              <tr>
                <td class="right"><?php echo $entry_sku; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_sku) { ?>
					<input type="checkbox" id="input-sku" name="extendedsearch_sku" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-sku" name="extendedsearch_sku" value="1" />
					<?php } ?>
					<label class="toggle" for="input-sku"><span class="toggle__handler"></span></label>
					</div>
				</td>
                <td class="right"><?php echo $entry_upc; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_upc) { ?>
					<input type="checkbox" id="input-upc" name="extendedsearch_upc" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-upc" name="extendedsearch_upc" value="1" />
					<?php } ?>
					<label class="toggle" for="input-upc"><span class="toggle__handler"></span></label>
					</div>
				</td>
              </tr>

              <tr>
                <td class="right"><?php echo $entry_ean; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_ean) { ?>
					<input type="checkbox" id="input-ean" name="extendedsearch_ean" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-ean" name="extendedsearch_ean" value="1" />
					<?php } ?>
					<label class="toggle" for="input-ean"><span class="toggle__handler"></span></label>
					</div>
				</td>
                <td class="right"><?php echo $entry_jan; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_jan) { ?>
					<input type="checkbox" id="input-jan" name="extendedsearch_jan" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-jan" name="extendedsearch_jan" value="1" />
					<?php } ?>
					<label class="toggle" for="input-jan"><span class="toggle__handler"></span></label>
					</div>
				</td>
              </tr>

              <tr>
                <td class="right"><?php echo $entry_isbn; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_isbn) { ?>
					<input type="checkbox" id="input-isbn" name="extendedsearch_isbn" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-isbn" name="extendedsearch_isbn" value="1" />
					<?php } ?>
					<label class="toggle" for="input-isbn"><span class="toggle__handler"></span></label>
					</div>
				</td>
                <td class="right"><?php echo $entry_mpn; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_mpn) { ?>
					<input type="checkbox" id="input-mpn" name="extendedsearch_mpn" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-mpn" name="extendedsearch_mpn" value="1" />
					<?php } ?>
					<label class="toggle" for="input-mpn"><span class="toggle__handler"></span></label>
					</div>
				</td>
              </tr>

              <tr>
                <td class="right"><?php echo $entry_location; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_location) { ?>
					<input type="checkbox" id="input-location" name="extendedsearch_location" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-location" name="extendedsearch_location" value="1" />
					<?php } ?>
					<label class="toggle" for="input-location"><span class="toggle__handler"></span></label>
					</div>
				</td>
                <td class="right"><?php echo $entry_attr; ?></td>
				<td>
					<div class="toggleWrapper">
					<?php if ($extendedsearch_attr) { ?>
					<input type="checkbox" id="input-attr" name="extendedsearch_attr" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" id="input-attr" name="extendedsearch_attr" value="1" />
					<?php } ?>
					<label class="toggle" for="input-attr"><span class="toggle__handler"></span></label>
					</div>
				</td>
              </tr>
            </table>

    </form>

  </div>
</div>
<?php echo $footer; ?>