<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
    </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
				<?php if($this->_SuperAdmin) : ?>
					<button type="button" class="btn btn-sm btn-info" onclick="forceSyncData()"><i class="fa fa-refresh"></i> Sync All</button>
				<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Code</label>
    <input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Name</label>
    <input type="text" class="form-control input-sm search-box" name="name" value="<?php echo $name; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Group</label>
    <select class="form-control input-sm filter" name="group" >
			<option value="all">ทั้งหมด</option>
			<?php echo select_customer_group($group); ?>
		</select>
  </div>

<!--
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Type</label>
    <select class="form-control input-sm filter" name="type">
			<option value="all">ทั้งหมด</option>
			<?php echo select_customer_type($type); ?>
		</select>
  </div>


	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>Grade</label>
    <select class="form-control input-sm filter" name="grade">
			<option value="all">ทั้งหมด</option>
			<?php echo select_customer_grade($grade); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
		<label>Area</label>
		<select class="form-control input-sm filter" name="area">
			<option value="all">ทั้งหมด</option>
			<?php echo select_customer_area($area); ?>
		</select>
	</div>
-->
	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		<label>Sales Team</label>
		<select class="form-control input-sm filter" name="saleTeam">
			<option value="all">ทั้งหมด</option>
			<option value="0" <?php echo is_selected('0', $saleTeam); ?> >-- No Sale Team --</option>
			<?php echo select_customer_sales_team($saleTeam); ?>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Payment</label>
    <select class="form-control input-sm filter" name="term">
			<option value="all">ทั้งหมด</option>
			<?php echo select_payment_term($term); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
    <label>Sales Emp.</label>
    <select class="form-control input-sm filter" name="slp">
			<option value="all">ทั้งหมด</option>
			<?php echo select_sale($slp); ?>
		</select>
  </div>

	<div class="col-lg-1 col-md-1 col-sm-2 col-xs-6 padding-5">
    <label>Status</label>
    <select class="form-control input-sm filter" name="status">
			<option value="all">ทั้งหมด</option>
			<option value="1" <?php echo is_selected($status, '1'); ?>>Active</option>
			<option value="0" <?php echo is_selected($status, '0'); ?>>Disactive</option>
		</select>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i>  Search</button>
  </div>

	<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i>  Reset</button>
  </div>

</div>
<hr class="margin-top-15 padding-5">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover border-1">
			<thead>
				<tr>
					<th style="width:50px;" class="middle text-center">#</th>
					<th style="width:100px;" class="middle">Code</th>
					<th style="min-width:250px;" class="middle">Name</th>
					<th style="width:150px;" class="middle">Group</th>
					<!--
					<th style="width:100px;" class="middle">Type</th>
					<th style="width:100px;" class="middle">Grade</th>
					<th style="width:100px;" class="middle">Area</th>
				-->
					<th style="width:150px;" class="middle">Sales Team</th>
					<th style="width:80px;" class="middle text-center">Status</th>
					<th style="width:100px;" class=""></th>
				</tr>
			</thead>
			<tbody>
		<?php if(!empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<tr style="font-size:12px;">
					<td class="middle text-center"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->CardCode; ?></td>
					<td class="middle"><?php echo $rs->CardName; ?></td>
					<td class="middle"><?php echo $rs->group_name; ?></td>
					<!--
					<td class="middle"><?php echo $rs->type_name; ?></td>
					<td class="middle"><?php echo $rs->grade_name; ?></td>
					<td class="middle"><?php echo $rs->area_name; ?></td>
				-->
					<td class="middle"><?php echo $rs->SaleTeamName; ?></td>
					<td class="middle text-center"><?php echo is_active($rs->Status); ?></td>
					<td class="text-right">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
						<?php if($this->pm->can_edit) : ?>
							<!--
						<button type="button" class="btn btn-mini btn-warning" onclick="getEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
					-->
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

<script src="<?php echo base_url(); ?>scripts/masters/customers.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/sync_customer.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
