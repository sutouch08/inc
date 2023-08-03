
	<form id="documentForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
    	<div class="col-lg-3 col-md-3-harf col-sm-4 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Prefix Quotation</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-8 padding-5">
				<label class="visible-xs">Prefix Quotation</label>
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_QUOTATION" required value="<?php echo $PREFIX_QUOTATION; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Run digit</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
				<label class="visible-xs">Run digit</label>
				<input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_QUOTATION" value="<?php echo $RUN_DIGIT_QUOTATION; ?>" />
			</div>
      <div class="divider-hidden"></div>
		</div>

		<div class="row">
			<div class="col-lg-3 col-md-3-harf col-sm-4 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Prefix Sales Order</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-8 padding-5">
					<label class="visible-xs">Prefix Sales Order</label>
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_ORDER" required value="<?php echo $PREFIX_ORDER; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Run digit</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
				<label class="visible-xs">Run digit</label>
				<input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_ORDER" value="<?php echo $RUN_DIGIT_ORDER; ?>" />
			</div>
      <div class="divider-hidden"></div>
		</div>

		<div class="row">
			<div class="col-lg-3 col-md-3-harf col-sm-4 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Prefix Customer Order</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-8 padding-5">
					<label class="visible-xs">Prefix Customer Order</label>
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_CUST_ORDER" required value="<?php echo $PREFIX_CUST_ORDER; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Run digit</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
				<label class="visible-xs">Run digit</label>
				<input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_CUST_ORDER" value="<?php echo $RUN_DIGIT_CUST_ORDER; ?>" />
			</div>
      <div class="divider-hidden"></div>
		</div>

		<div class="row">
			<div class="col-lg-3 col-md-3-harf col-sm-4 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Prefix Discount Rule</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-8 padding-5">
					<label class="visible-xs">Prefix Discount Rule</label>
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_RULE" required value="<?php echo $PREFIX_RULE; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Run digit</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
				<label class="visible-xs">Run digit</label>
				<input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_RULE" value="<?php echo $RUN_DIGIT_RULE; ?>" />
			</div>
      <div class="divider-hidden"></div>
		</div>

		<div class="row">
			<div class="col-lg-3 col-md-3-harf col-sm-4 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Prefix Promotion</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-8 padding-5">
					<label class="visible-xs">Prefix Promotion</label>
				<input type="text" class="form-control input-sm input-small text-center prefix" name="PREFIX_POLICY" required value="<?php echo $PREFIX_POLICY; ?>" />
			</div>
      <div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 hidden-xs">
				<span class="form-control left-label width-100 text-right">Run digit</span>
			</div>
      <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
				<label class="visible-xs">Run digit</label>
				<input type="text" class="form-control input-sm input-small text-center digit" required name="RUN_DIGIT_POLICY" value="<?php echo $RUN_DIGIT_POLICY; ?>" />
			</div>
      <div class="divider-hidden"></div>
		</div>


		<div class="row">
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
