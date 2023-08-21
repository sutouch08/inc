<?php $this->load->view('include/header'); ?>

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
			<input type="text" name="uname" id="uname" class="form-control width-100" maxlength="50" value="<?php echo $user->uname; ?>" disabled/>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="uname-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Display name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="dname" id="dname" class="form-control width-100" maxlength="100" value="<?php echo $user->name; ?>" />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="dname-error"></div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="emp_id">
				<option value="">-No Employee-</option>
				<?php echo select_employee($user->emp_id); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="sale_id">
			<?php echo select_saleman($user->sale_id); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Team</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="team_id">				
			<?php echo select_sales_team($user->team_id); ?>
      </select>
    </div>
  </div>


  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">User Group</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="group_id">
        <?php echo select_user_group($user->group_id); ?>
      </select>
    </div>
	</div>


  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Permission Profile</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" name="profile" id="profile">
        <option value="">Please Select</option>
        <?php echo select_profile($user->id_profile); ?>
      </select>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="profile-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="active" <?php echo is_checked($user->active, '1'); ?> />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>


	<div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="update()">Update</button>
      </p>
    </div>
  </div>

	<input type="hidden" id="user_id" value="<?php echo $user->id; ?>">
</form>


<script>
	$('#emp_id').select2();
	$('#sale_id').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/users/users.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
