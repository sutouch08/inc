<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3 class="title"><?php echo $this->title; ?> </h3>
  </div>
</div><!-- End Row -->
<hr class="">
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>User</label>
    <input type="text" class="form-control search-box" name="uname" value="<?php echo $uname; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>Doc Num</label>
    <input type="text" class="form-control" name="docNum" value="<?php echo $docNum; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>Doc Type</label>
    <select class="form-control filter" name="docType">
			<option value="all">ทั้งหมด</option>
			<?php echo select_doc_type($docType); ?>
		</select>
  </div>



	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>action</label>
    <select class="form-control filter" name="action">
			<option value="all">ทั้งหมด</option>
			<?php echo select_action($action); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>Ip Address</label>
    <input type="text" class="form-control search-box" name="ip_address" value="<?php echo $ip_address; ?>" />
  </div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Date</label>
		<div class="input-daterange input-group width-100">
			<input type="text" class="form-control width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
			<input type="text" class="form-control width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
		</div>
	</div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<input type="hidden" name="search" value="1" />
</form>
<hr/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped border-1" style="min-width:800px;">
			<thead>
				<tr>
					<th class="fix-width-80 middle text-center">#</th>
					<th class="fix-width-150 middle">Date Time</th>
					<th class="fix-width-100 middle">User</th>
					<th class="fix-width-80 middle text-center">Action</th>
					<th class="fix-width-80 middle text-center">Doc Type</th>
					<th class="fix-width-150 middle">Document Number</th>
					<th class="min-width-150 middle">IP Address</th>
				</tr>
			</thead>
			<tbody>
			<?php if( ! empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>

				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo thai_date($rs->date_upd, TRUE); ?></td>
						<td class="middle"><?php echo $rs->uname; ?></td>
						<td class="middle text-center"><?php echo $rs->action; ?></td>
						<td class="middle text-center"><?php echo $rs->docType; ?></td>
						<td class="middle"><?php echo $rs->docNum; ?></td>
						<td class="middle"><?php echo $rs->ip_address; ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/users/access_logs.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
