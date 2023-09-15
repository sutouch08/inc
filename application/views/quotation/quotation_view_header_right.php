<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5 last">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Web No.</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" class="form-control input-xs" value="<?php echo $order->code; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Status</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" class="form-control input-xs" value="<?php echo statusName($order->Status, $order->Review, $order->Approved); ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label-xs no-padding-right">Orignal No.</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" class="form-control input-xs" value="<?php echo $order->OriginalSQ; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">SQ No.</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" class="form-control input-xs" value="<?php echo $order->DocNum; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Posting Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" id="TextDate" class="form-control input-xs" value="<?php echo thai_date($order->TextDate, FALSE); ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Valid Until</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" id="ShipDate" class="form-control input-xs" value="<?php echo thai_date($order->DocDueDate, FALSE); ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Document Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<input type="text" id="DocDate" class="form-control input-xs" value="<?php echo thai_date($order->DocDate, FALSE); ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-4 control-label-xs no-padding-right">Payment</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-8">
				<select class="form-control input-xs" id="payment" name="payment" disabled>
					<?php echo select_payment_term($order->Payment); ?>
				</select>
			</div>
		</div>
	</div>
</div>
