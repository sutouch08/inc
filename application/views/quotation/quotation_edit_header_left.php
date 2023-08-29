<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardCode" class="form-control input-sm" value="<?php echo $order->CardCode; ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Name</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="CardName" class="form-control input-sm" value="<?php echo $order->CardName; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Contact</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="contact" class="form-control input-sm" maxlength="100" value="<?php echo $order->ContactPerson; ?>" readonly/>
        <input type="hidden" id="CntctCode" value="<?php echo $order->CntctCode; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Customer Ref</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="NumAtCard" class="form-control input-sm" maxlength="100" value="<?php echo $order->NumAtCard; ?>" />
        <input type="hidden" id="phone" value="<?php echo $order->Phone; ?>" />
      </div>
    </div>

		<div class="form-group">
			<label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Payment</label>
			<div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
				<select class="form-control input-sm" id="payment" name="payment" >
					<?php echo select_payment_term($order->Payment); ?>
				</select>
			</div>
		</div>


		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Bill To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="billToCode" onchange="get_address_bill_to()">
        	<?php echo select_bill_to_code($order->CardCode, $order->PayToCode); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right"></label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <textarea id="BillTo" class="autosize autosize-transition form-control"><?php echo $order->Address; ?></textarea>
      </div>
    </div>
  </div>
</div>
