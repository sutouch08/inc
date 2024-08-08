<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5 last">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Web No.</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<input type="text" id="code" class="form-control input-xs" value="<?php echo $order->code; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Status</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<input type="text" class="form-control input-xs" id="statusName" value="<?php echo statusName($order->Status, $order->Review, $order->Approved); ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">SQ No.</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<div class="input-group width-100">
					<input type="text" class="form-control input-xs" style="border-right:0;" id="SQNO" value="<?php echo $order->SQNO; ?>" disabled/>
					<span class="input-group-addon" style="line-height:1; font-size:8px; height:21px; border-left:0; background-color:#c5c5c5;" onclick="showSqModal()">
						<i class="fa fa-bars"></i>
					</span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Posting Date</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<input type="text" id="TextDate" class="form-control input-xs" value="<?php echo thai_date($order->TextDate, FALSE); ?>" readonly/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Due Date</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<input type="text" id="ShipDate" class="form-control input-xs" value="<?php echo thai_date($order->DocDueDate, FALSE); ?>" readonly/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Document Date</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<input type="text" id="DocDate" class="form-control input-xs" value="<?php echo thai_date($order->DocDate, FALSE); ?>" readonly/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-7-harf col-md-7 col-sm-6 col-xs-12 control-label-xs no-padding-right">Payment</label>
			<div class="col-lg-4-harf col-md-5 col-sm-6 col-xs-12">
				<select class="form-control input-xs" id="payment" name="payment">
					<?php echo select_payment_term($order->Payment); ?>
				</select>
			</div>
		</div>
	</div>
</div>
