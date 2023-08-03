<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="padding-5"/>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">

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
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Sales Team</label>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<input type="text" class="form-control input-sm" value="<?php echo $SaleTeamName; ?>" disabled />
				</div>
				<div class="help-block col-xs-12 col-sm-reset inline red"></div>
			</div>

			<!--
			<div class="form-group">
		    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Type</label>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<select id="TypeCode" class="form-control" disabled>
						<option value="">เลือกรายการ</option>
						<?php echo select_customer_type($TypeCode); ?>
					</select>
		    </div>
		    <div class="help-block col-xs-12 col-sm-reset inline red" id="type-error"></div>
		  </div>

			<div class="form-group">
		    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Grade</label>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<select id="GradeCode" class="form-control" disabled>
						<option value="">เลือกรายการ</option>
					<?php echo select_customer_grade($GradeCode); ?>
					</select>
		    </div>
		    <div class="help-block col-xs-12 col-sm-reset inline red" id="grade-error"></div>
		  </div>


			<div class="form-group">
		    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Region</label>
		    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<select id="RegionCode" class="form-control" disabled>
						<option value="">เลือกรายการ</option>
					<?php echo select_customer_region($RegionCode); ?>
					</select>
		    </div>
		    <div class="help-block col-xs-12 col-sm-reset inline red" id="region-error"></div>
		  </div>


			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Area</label>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<select id="AreaCode" class="form-control" disabled>
						<option value="">เลือกรายการ</option>
						<?php echo select_customer_area($AreaCode); ?>
					</select>
				</div>
				<div class="help-block col-xs-12 col-sm-reset inline red" id="area-error"></div>
			</div>
		-->

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Status</label>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding-top:5px;">
					<label style="margin-left:20px;">
						<span class="lbl">  <?php echo ($Status == 1 ? "Active" : "Inactive"); ?></span>
					</label>
				</div>
			</div>

			<div class="divider-hidden">

			</div>
		</form>

	</div><!--/ col-sm-9  -->
</div><!--/ row  -->
<script src="<?php echo base_url(); ?>scripts/masters/customers.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
