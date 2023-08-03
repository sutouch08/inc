<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30"/>
<form class="form-horizontal">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Username</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" class="form-control input-sm" value="<?php echo $approver->uname; ?>" disabled/>
    </div>
		<div class="col-xs-12 col-sm-reset inline red margin-top-5" id="user-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Sales Team</label>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 table-responsive">
			<table class="table table-striped border-1">
				<thead>
					<tr>
						<th class="fix-width-100">Code</th>
						<th class="min-width-100">Name</th>
						<th class="fix-width-40"></th>
					</tr>
				</thead>
				<tbody>
		<?php if(!empty($sales_team)) : ?>
			<?php foreach($sales_team as $rs) : ?>
				<?php $chk = (! empty($ap_team[$rs->id]) ? 'checked' : ''); ?>
				<tr>
					<td><?php echo $rs->code; ?></td>
					<td><?php echo $rs->name; ?></td>
					<td class="text-center">
						<label>
							<input type="checkbox" class="ace chk-team" value="<?php echo $rs->id; ?>" <?php echo $chk; ?> disabled/>
							<span class="lbl"></span>
						</label>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
				</tbody>
			</table>
    </div>
		<div class="col-sm-reset inline red margin-top-5" id="team-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Brand</label>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 table-responsive">
			<table class="table table-striped border-1">
				<thead>
					<tr>
						<th class="fix-width-40 text-center"></th>
						<th class="fix-width-100">Code</th>
						<th class="min-width-100">Name</th>
						<th class="fix-width-100">Max Disc(%)</th>
					</tr>
				</thead>
				<tbody>
		<?php if(!empty($brand)) : ?>
			<?php foreach($brand as $rs) : ?>
				<?php $chk = isset($ap_brand[$rs->id]) ? 'checked' : '';  ?>
				<?php $val = isset($ap_brand[$rs->id]) ? $ap_brand[$rs->id] : 0.00; ?>
				<tr>
					<td class="text-center">
						<label>
							<input type="checkbox" class="ace chk-brand" value="<?php echo $rs->id; ?>" data-id="<?php echo $rs->id; ?>" <?php echo $chk; ?> disabled/>
							<span class="lbl"></span>
						</label>
					</td>
					<td><?php echo $rs->code; ?></td>
					<td><?php echo $rs->name; ?></td>
					<td>
						<input type="number" class="form-control input-sm text-center disc" id="brand-disc-<?php echo $rs->id; ?>" value="<?php echo $val; ?>" disabled/>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
				</tbody>
			</table>
    </div>
		<div class="col-sm-reset inline red margin-top-5" id="team-error"></div>
  </div>

	<div class="divider-hidden"></div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label>
				<input type="checkbox" class="ace" id="status" <?php echo is_checked(1, $approver->status); ?> disabled />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>

	<div class="divider-hidden"></div>
</form>

<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
