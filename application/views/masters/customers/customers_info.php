
<form class="form-horizontal" style="margin-top:30px;">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Code</label>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
      <input type="text" class="form-control input-sm" value="<?php echo $CardCode; ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="code-error"></div>
  </div>



  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Name</label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<input type="text" class="form-control input-sm" value="<?php echo $CardName; ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
  </div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">TAX ID</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<input type="text" id="LicTradNum" class="form-control input-sm" value="<?php echo $LicTradNum; ?>" disabled />
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
	</div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Group</label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<input type="text" class="form-control input-sm" value="<?php echo $group_name; ?>" disabled />
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="group-error"></div>
  </div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Payment Term</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<input type="text" class="form-control input-sm" value="<?php echo $term_name; ?>" disabled />
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline red" id="term-error"></div>
	</div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Sales Employee</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<input type="text" class="form-control input-sm" value="<?php echo $sale_name; ?>" disabled />
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline red" id="slp-error"></div>
	</div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Type</label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<select id="TypeCode" class="form-control">
				<option value="">เลือกรายการ</option>
				<?php echo select_customer_type($TypeCode); ?>
			</select>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="type-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Grade</label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<select id="GradeCode" class="form-control" >
				<option value="">เลือกรายการ</option>
			<?php echo select_customer_grade($GradeCode); ?>
			</select>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="grade-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Region</label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<select id="RegionCode" class="form-control">
				<option value="">เลือกรายการ</option>
			<?php echo select_customer_region($RegionCode); ?>
			</select>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline red" id="region-error"></div>
  </div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Area</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<select id="AreaCode" class="form-control">
				<option value="">เลือกรายการ</option>
				<?php echo select_customer_area($AreaCode); ?>
			</select>
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline red" id="area-error"></div>
	</div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Status</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top:5px;">
			<label style="margin-left:20px;">
				<span class="lbl">  <?php echo ($Status == 1 ? "Active" : "Inactive"); ?></span>
			</label>
		</div>
	</div>
	<!--
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Status</label>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top:5px;">
			<label style="margin-left:20px;">
				<input type="radio" class="ace" name="active" value="1" <?php echo is_checked($Status, '1'); ?> />
				<span class="lbl">  Active</span>
			</label>
			<label style="margin-left:20px;">
				<input type="radio" class="ace" name="active" value="0" <?php echo is_checked($Status, '0'); ?> />
				<span class="lbl">  Inactive</span>
			</label>
		</div>
		<div class="help-block col-xs-12 col-sm-reset inline red"></div>
	</div>
-->
	<div class="divider-hidden">

	</div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right"></label>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="update()">Update</button>
      </p>
    </div>
    <div class="help-block col-xs-12 col-sm-reset inline">
      &nbsp;
    </div>
  </div>

	<input type="hidden" id="id" value="<?php echo $id; ?>">
</form>
