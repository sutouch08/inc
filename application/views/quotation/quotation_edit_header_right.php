<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 padding-5 last">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Web No.</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="code" class="form-control input-sm" value="<?php echo $order->code; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Original SQ</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<input type="text" id="OriginalSQ" class="form-control input-sm" value="<?php echo $order->OriginalSQ; ?>" disabled/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Document Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
				<input type="text" id="DocDate" class="form-control input-sm" value="<?php echo thai_date($order->DocDate, FALSE); ?>" readonly/>
				<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Valid Until</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
				<input type="text" id="ShipDate" class="form-control input-sm" value="<?php echo thai_date($order->DocDueDate, FALSE); ?>" readonly/>
				<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Posting Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
				<input type="text" id="TextDate" class="form-control input-sm" value="<?php echo thai_date($order->TextDate, FALSE); ?>" readonly/>
				<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>

		<div class="form-group">
      <label class="col-lg-7 col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Ship To</label>
      <div class="col-lg-5 col-md-4 col-sm-5 col-xs-12">
        <select class="form-control input-sm" id="shipToCode" onchange="get_address_ship_to()">
					<?php echo select_ship_to_code($order->CardCode, $order->ShipToCode); ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 col-md-2 control-label no-padding-right hidden-sm"></label>
      <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
        <textarea id="ShipTo" class="autosize autosize-transition form-control"><?php echo $order->Address2; ?></textarea>
      </div>
    </div>
	</div>

</div>
