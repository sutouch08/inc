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
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Contact Person</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="contact" class="form-control input-sm" maxlength="100" value="<?php echo $order->ContactPerson; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Phone Number</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <input type="text" id="phone" class="form-control input-sm" maxlength="50" value="<?php echo $order->Phone; ?>" />
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
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Channels</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
				<select class="form-control input-sm" id="channels" name="channels" >
					<?php echo select_channels($order->Channels); ?>
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
        <!--<span class="badge badge-yellow pull-right margin-top-5"
        style="padding-bottom:0px; padding-top: 0px; border-radius:3px; cursor:pointer;" onclick="editShipTo()">
          <i class="fa fa-ellipsis-h"></i>
        </span>-->
      </div>
    </div>


    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Ship To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="shipToCode" onchange="get_address_ship_to()">
        	<?php echo select_ship_to_code($order->CardCode, $order->ShipToCode); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right"></label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <textarea id="ShipTo" class="autosize autosize-transition form-control"><?php echo $order->Address2; ?></textarea>
        <!--<span class="badge badge-yellow pull-right margin-top-5"
        style="padding-bottom:0px; padding-top: 0px; border-radius:3px; cursor:pointer;" onclick="editShipTo()">
          <i class="fa fa-ellipsis-h"></i>
        </span>-->
      </div>
    </div>



  </div>
</div>
