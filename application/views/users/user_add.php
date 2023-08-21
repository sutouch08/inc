<?php $this->load->view('include/header'); ?>
<script>
	var USE_STRONG_PWD = <?php echo getConfig('USE_STRONG_PWD'); ?>;
</script>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30"/>
<form class="form-horizontal" id="addForm" method="post">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Username</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="uname" id="uname"
			class="form-control" maxlength="50"
			onkeyup="validCode(this)"
			placeholder="Allow &nbsp; a-z,  A-Z,  0-9,  &quot;-&quot;,  &quot;_&quot;,  &quot;.&quot;,  &quot;@&quot;"
			autofocus required />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="uname-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="dname" id="dname" class="form-control" maxlength="100" required />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="dname-error"></div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="emp_id">
				<option value="">-No Employee-</option>
				<?php echo select_employee(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="sale_id">
			<?php echo select_saleman(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Team</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="team_id">				
			<?php echo select_sales_team(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">User Group</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="group_id">
        <?php echo select_user_group(); ?>
      </select>
    </div>
	</div>

	<div class="divider"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">New password</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="pwd" id="pwd" class="form-control" required />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 red" id="pwd-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Confirm password</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="cm-pwd" id="cm-pwd" class="form-control" required />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 red" id="cm-pwd-error"></div>
  </div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Permission Profile</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" name="profile" id="profile">
        <option value="">Please Select</option>
        <?php echo select_profile(); ?>
      </select>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="profile-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="active" checked />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="force_reset" checked />
				<span class="lbl">&nbsp; Force user to change password</span>
			</label>
    </div>
  </div>


	<div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="saveAdd()">Add</button>
      </p>
    </div>
  </div>
</form>

<script>
	$('#emp_id').select2();
	$('#sale_id').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/users/users.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
