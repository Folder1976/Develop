<div class="form-group required">
  <?php if (substr($route, 0, 9) == 'checkout/') { ?>
  <label class="control-label" for="input-payment-captcha"><?php echo $entry_captcha; ?></label>
  <input type="text" name="captcha" id="input-payment-captcha" class="form-control" />
  <img src="index.php?route=captcha/basic_captcha/captcha" alt="" />
 <?php } else { ?>
  <label class="col-sm-12 control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
  <div class="col-sm-8">
    <img src="index.php?route=extension/captcha/basic_captcha/captcha" alt="" /><br /><br />
     <input type="text" name="captcha" id="input-captcha" class="form-control" />
    <?php if ($error_captcha) { ?>
    <div class="text-danger"><?php echo $error_captcha; ?></div>
    <?php } ?>
  </div>
  <?php } ?>
</div>
