<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-xs btn-success top-btn" onclick="addNew()"><i class="fa fa-plus"></i>  Add new</button>
		</p>
	</div>
</div>
<hr class="">
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6">
		<label>User</label>
		<input type="text" class="form-control input-sm search-box" name="uname" value="<?php echo $uname; ?>" />
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
		<label>Sales team</label>
		<select class="form-control input-sm" name="team" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<?php echo select_team($team); ?>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
		<label>Brand</label>
		<select class="form-control input-sm" name="brand" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<?php echo select_brand($brand); ?>
		</select>
	</div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
		<label>Status</label>
		<select class="form-control input-sm" name="status" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>Inactive</option>
		</select>
	</div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
		<label class="display-block not-show">ok</label>
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
	</div>
	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
		<label class="display-block not-show">reset</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
	</div>
</div>
</form>
<hr class="margin-top-10 margin-bottom-10">

<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="fix-width-60 text-center">#</th>
					<th class="fix-width-150">Username</th>
					<th class="min-width-250">Name</th>
					<th class="fix-width-100 text-center">Status</th>
					<th class="fix-width-120"></th>
				</tr>
			</thead>
			<tbody>
<?php if(!empty($data)) : ?>
	<?php $no = $this->uri->segment($this->segment) + 1; ?>
	<?php foreach($data as $rs) : ?>
				<tr>
					<td class="middle text-center"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->uname; ?></td>
					<td class="middle"><?php echo $rs->name; ?></td>
					<td class="middle text-center"><?php echo is_active($rs->status); ?></td>
					<td class="middle text-right">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
					<?php if($this->pm->can_edit) : ?>
						<button type="button" class="btn btn-mini btn-warning" onclick="getEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
					<?php endif; ?>
					<?php if($this->pm->can_delete) : ?>
						<button type="button" class="btn btn-mini btn-danger" onclick="getDelete(<?php echo $rs->id; ?>, '<?php echo $rs->uname; ?>')"><i class="fa fa-trash"></i></button>
					<?php endif; ?>
					</td>
				</tr>
		<?php $no++; ?>
	<?php endforeach; ?>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
