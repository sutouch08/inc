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
				<input type="text" id="code" class="form-control input-sm" value="<?php echo $order->OriginalSQ; ?>" disabled/>
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

		<div class="form-group hide">
			<label class="col-lg-8-harf col-md-8 col-sm-7 col-xs-12 control-label no-padding-right">Posting Date</label>
			<div class="col-lg-3-harf col-md-4 col-sm-5 col-xs-12">
				<span class="input-icon input-icon-right">
				<input type="text" id="TextDate" class="form-control input-sm" value="<?php echo thai_date($order->TextDate, FALSE); ?>" readonly/>
				<i class="ace-icon fa fa-calendar-o"></i>
				</span>
			</div>
		</div>


		<div class="form-group">
      <label class="col-lg-7 col-md-6 col-sm-6 col-xs-12 control-label no-padding-right">CEO</label>
      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<select class="form-control input-sm" id="dimCode1" name="dimCode1" >
					<option value="">Please Select</option>
					<?php echo select_cost_center(1, $order->dimCode1); ?>
				</select>
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-7 col-md-6 col-sm-6 col-xs-12 control-label no-padding-right">COO/CFO</label>
      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<select class="form-control input-sm" id="dimCode2" name="dimCode2" >
					<option value="">Please Select</option>
					<?php echo select_cost_center(2, $order->dimCode2); ?>
				</select>
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-7 col-md-6 col-sm-6 col-xs-12 control-label no-padding-right">สายงานขายและการตลาด</label>
      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<select class="form-control input-sm" id="dimCode3" name="dimCode3" >
					<option value="">Please Select</option>
					<?php echo select_cost_center(3, $order->dimCode3); ?>
				</select>
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-7 col-md-6 col-sm-6 col-xs-12 control-label no-padding-right">ฝ่าย</label>
      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<select class="form-control input-sm" id="dimCode4" name="dimCode4" >
					<option value="">Please Select</option>
					<?php echo select_cost_center(4, $order->dimCode4); ?>
				</select>
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-7 col-md-6 col-sm-6 col-xs-12 control-label no-padding-right">แผนก</label>
      <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<select class="form-control input-sm" id="dimCode5" name="dimCode5" >
					<option value="">Please Select</option>
					<?php echo select_cost_center(5, $order->dimCode5); ?>
				</select>
      </div>
    </div>

	</div>

</div>
