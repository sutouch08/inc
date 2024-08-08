
	<form id="documentForm" method="post" action="<?php echo $this->home; ?>/update_config">

    <div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h4 class="title">Quotation</h4></div>
			<div class="divider" style="margin-top:5px;">	</div>
      <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Prefix</label>
				<input type="text" class="form-control input-sm text-center prefix"
				maxlength="3" name="PREFIX_QUOTATION" onkeyup="validPrefix(this)"
				value="<?php echo $PREFIX_QUOTATION; ?>"/>
			</div>
      <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Running digit</label>
				<select class="form-control input-sm" name="RUN_DIGIT_QUOTATION">
					<option value="3" <?php echo is_selected('3', $RUN_DIGIT_QUOTATION); ?>>3</option>
					<option value="4" <?php echo is_selected('4', $RUN_DIGIT_QUOTATION); ?>>4</option>
					<option value="5" <?php echo is_selected('5', $RUN_DIGIT_QUOTATION); ?>>5</option>
					<option value="6" <?php echo is_selected('6', $RUN_DIGIT_QUOTATION); ?>>6</option>
					<option value="7" <?php echo is_selected('7', $RUN_DIGIT_QUOTATION); ?>>7</option>					
				</select>
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Review</label>
				<select class="form-control input-sm" name="QUOTATION_REVIEW">
					<option value="0" <?php echo is_selected('0', $QUOTATION_REVIEW); ?>>OFF</option>
					<option value="1" <?php echo is_selected('1', $QUOTATION_REVIEW); ?>>ON</option>
				</select>
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Approve</label>
				<select class="form-control input-sm" name="QUOTATION_APPROVE">
					<option value="1" <?php echo is_selected('1', $QUOTATION_APPROVE); ?>>ON</option>
					<option value="0" <?php echo is_selected('0', $QUOTATION_APPROVE); ?>>OFF</option>
				</select>
			</div>

			<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Begin Disc (%)</label>
				<div class="input-group width-100">
					<input type="number" class="form-control input-sm text-center" required name="DISCOUNT_TO_APPROVE_SQ" value="<?php echo $DISCOUNT_TO_APPROVE_SQ; ?>" />
					<span class="input-group-addon">%</span>
				</div>
			</div>

			<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Begin Amount</label>
				<input type="number" class="form-control input-sm text-center" required name="AMOUNT_TO_APPROVE_SQ" value="<?php echo $AMOUNT_TO_APPROVE_SQ; ?>" />
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Auto Interface</label>
				<select class="form-control input-sm" name="QUOTATION_AUTO_INTERFACE">
					<option value="1" <?php echo is_selected('1', $QUOTATION_AUTO_INTERFACE); ?>>ON</option>
					<option value="0" <?php echo is_selected('0', $QUOTATION_AUTO_INTERFACE); ?>>OFF</option>
				</select>
			</div>
		</div>

		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h4 class="title">Sales Order</h4></div>
			<div class="divider" style="margin-top:5px;">	</div>
      <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Prefix</label>
				<input type="text" class="form-control input-sm text-center prefix"
					maxlength="3" name="PREFIX_ORDER" onkeyup="validPrefix(this)"
				value="<?php echo $PREFIX_ORDER; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Running digit</label>
				<select class="form-control input-sm" name="RUN_DIGIT_ORDER">
					<option value="3" <?php echo is_selected('3', $RUN_DIGIT_ORDER); ?>>3</option>
					<option value="4" <?php echo is_selected('4', $RUN_DIGIT_ORDER); ?>>4</option>
					<option value="5" <?php echo is_selected('5', $RUN_DIGIT_ORDER); ?>>5</option>
					<option value="6" <?php echo is_selected('6', $RUN_DIGIT_ORDER); ?>>6</option>
					<option value="7" <?php echo is_selected('7', $RUN_DIGIT_ORDER); ?>>7</option>
				</select>
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Review</label>
				<select class="form-control input-sm" name="ORDER_REVIEW">
					<option value="0" <?php echo is_selected('0', $ORDER_REVIEW); ?>>OFF</option>
					<option value="1" <?php echo is_selected('1', $ORDER_REVIEW); ?>>ON</option>
				</select>
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Approve</label>
				<select class="form-control input-sm" name="ORDER_APPROVE">
					<option value="1" <?php echo is_selected('1', $ORDER_APPROVE); ?>>ON</option>
					<option value="0" <?php echo is_selected('0', $ORDER_APPROVE); ?>>OFF</option>
				</select>
			</div>

			<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Begin Disc (%)</label>
				<div class="input-group width-100">
					<input type="number" class="form-control input-sm text-center" required name="DISCOUNT_TO_APPROVE_SO" value="<?php echo $DISCOUNT_TO_APPROVE_SO; ?>" />
					<span class="input-group-addon">%</span>
				</div>
			</div>

			<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Begin Amount</label>
				<input type="number" class="form-control input-sm text-center" required name="AMOUNT_TO_APPROVE_SO" value="<?php echo $AMOUNT_TO_APPROVE_SO; ?>" />
			</div>

			<div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-4 padding-5">
				<label>Auto Interface</label>
				<select class="form-control input-sm" name="ORDER_AUTO_INTERFACE">
					<option value="1" <?php echo is_selected('1', $ORDER_AUTO_INTERFACE); ?>>ON</option>
					<option value="0" <?php echo is_selected('0', $ORDER_AUTO_INTERFACE); ?>>OFF</option>
				</select>
			</div>
		</div>

		<div class="row">
      <div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
      <div class="col-lg-6-harf col-md-8-harf col-sm-9 hidden-xs padding-5 text-right">
			<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
      	<button type="button" class="btn btn-sm btn-success input-small" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
      </div>
			<div class="col-xs-12 visible-xs padding-5 text-center">
			<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>
		</div>
  </form>
