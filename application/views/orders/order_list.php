<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
<?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
    <label>Web No.</label>
    <input type="text" class="width-100 search-box" name="code"  value="<?php echo $code; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>Customer</label>
		<input type="text" class="width-100 search-box" name="customer" value="<?php echo $customer; ?>" />
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>Project</label>
		<input type="text" class="width-100 search-box" name="project" value="<?php echo $project; ?>" />
	</div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>User</label>
    <select class="width-100 filter" name="user_id" id="user_id">
			<option value="all">ทั้งหมด</option>
			<?php echo select_user($user_id); ?>
		</select>
	</div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Owner</label>
		<select class="width-100 filter" name="emp_id" id="emp_id">
			<option value="all">ทั้งหมด</option>
			<?php echo select_employee($emp_id); ?>
		</select>
	</div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>Sale Employee</label>
		<select class="width-100 filter" name="sale_id" id="sale_id">
			<option value="all">ทั้งหมด</option>
			<?php echo select_saleman($sale_id); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>SQ No.</label>
		<input type="text" class="width-100 search-box" name="SQNO" value="<?php echo $SQNO; ?>" />
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>SAP No.</label>
		<input type="text" class="width-100 search-box" name="doc_num" value="<?php echo $doc_num; ?>" />
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Review</label>
		<select class="width-100 filter" name="review">
			<option value="all">ทั้งหมด</option>
			<option value="P" <?php echo is_selected('P', $review); ?>>Pending</option>
			<option value="A" <?php echo is_selected('A', $review); ?>>Confirmed</option>
			<option value="R" <?php echo is_selected('R', $review); ?>>Rejected</option>
			<option value="S" <?php echo is_selected('S', $review); ?>>System</option>
		</select>
	</div>


	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		<label>Approval</label>
		<select class="width-100 filter" name="approval">
			<option value="all">ทั้งหมด</option>
			<option value="P" <?php echo is_selected('P', $approval); ?>>Pending</option>
			<option value="A" <?php echo is_selected('A', $approval); ?>>Approved</option>
			<option value="R" <?php echo is_selected('R', $approval); ?>>Rejected</option>
			<option value="S" <?php echo is_selected('S', $approval); ?>>System</option>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
		<label>สถานะ</label>
		<select class="width-100 filter" name="status">
			<option value="all">ทั้งหมด</option>
			<option value="-1" <?php echo is_selected('-1', $status); ?>>Draft</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>Pending</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>Success</option>
			<option value="2" <?php echo is_selected('2', $status); ?>>Canceled</option>
			<option value="3" <?php echo is_selected('3', $status); ?>>Failed</option>
		</select>
	</div>

	<div class="col-lg-2-harf col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Date</label>
		<div class="input-daterange input-group width-100">
			<input type="text" class="width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
			<input type="text" class="width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
		</div>
	</div>

	<div class="divider-hidden visible-xs"></div>
	<div class="divider-hidden visible-xs"></div>

	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label class="display-block not-show hidden-xs">buton</label>
		<button type="submit" class="btn btn-xs btn-primary btn-block" onclick="getSearch()" >Search</button>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
		<label class="display-block not-show hidden-xs">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
	</div>
</div>

<input type="hidden" id="onlyMe" name="onlyMe" value="<?php echo $onlyMe; ?>" />
<input type="hidden" name="search" value="1" />
</form>
<hr class="padding-5 margin-top-10"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="double-scroll">
		<table class="table table-striped table-hover dataTable border-1" style="margin-bottom:10px; min-width:1460px; border-collapse:inherit;">
			<thead>
				<tr style="font-size:10px;">
					<th class="middle" style="width:110px;"></th>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-80 middle">Date</th>
					<th class="fix-width-100 middle">Web No.</th>
					<th class="fix-width-100 middle">Customer code</th>
					<th class="min-width-250 middle">Customer name</th>
					<th class="fix-width-120 middle">Project</th>
					<th class="fix-width-100 middle text-right">Amount</th>
					<th class="fix-width-80 middle text-center">Review</th>
					<th class="fix-width-80 middle text-center">Approval</th>
					<th class="fix-width-80 middle text-center">Status</th>
					<th class="fix-width-100 middle text-center">User</th>
					<th class="fix-width-100 middle text-center">SQ No.</th>
					<th class="fix-width-100 middle text-center">SAP No.</th>
				</tr>
			</thead>
			<tbody style="font-size:12px;">
		<?php if( ! empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<tr>
					<td class="middle">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
					<?php if($this->pm->can_edit && ($rs->Status == 0 OR $rs->Status == -1 OR $rs->Status == 3)) : ?>
						<button type="button" class="btn btn-mini btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
					<?php endif; ?>
					<?php if($this->pm->can_delete && $rs->Status != 1 && $rs->Status != 2) : ?>
						<button type="button" class="btn btn-mini btn-danger" onclick="cancleOrder('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
					<?php endif; ?>
					</td>
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo thai_date($rs->DocDate, FALSE); ?></td>
					<td class="middle"><?php echo $rs->code; ?></td>
					<td class="middle"><?php echo $rs->CardCode; ?></td>
					<td class="middle"><?php echo $rs->CardName; ?></td>
					<td class="middle"><?php echo $rs->Project; ?></td>
					<td class="middle text-right"><?php echo number($rs->DocTotal, 2); ?></td>
					<td class="middle text-center">
						<?php if($rs->Status != -1 && $rs->Status != 2) : ?>
							<?php if($rs->Review == 'A') : ?>
								<span class="green">Confirmed</span>
							<?php elseif($rs->Review == 'R') : ?>
								<a href="javascript:void(0)" class="red" onclick="showReason('<?php echo $rs->code; ?>')">Rejected</a>
							<?php elseif($rs->Review == 'S') : ?>
								<span class="green">System</span>
							<?php else : ?>
								<span class="orange">Pending</span>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<?php if($rs->Status == 0 OR $rs->Status == 1) : ?>
							<?php if($rs->must_approve == 0 && $rs->Review != 'P' && $rs->Review != 'R') : ?>
								<span class="green">System</span>
							<?php else : ?>
								<?php if($rs->Approved == 'P') : ?>
									<span class="orange">Pending</span>
								<?php elseif($rs->Approved == 'A') : ?>
									<span class="green">Approved</span>
								<?php elseif($rs->Approved == 'R') : ?>
								<a href="javascript:void(0)" class="red" onclick="showReason('<?php echo $rs->code; ?>')">Rejected</a>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<?php if($rs->Status == -1) : ?>
							<span class="purple">Draft</span>
						<?php elseif($rs->Status == 0) : ?>
							<span class="orange">Pending</span>
						<?php elseif($rs->Status == 1) : ?>
							<span class="green">Success</span>
						<?php elseif($rs->Status == 2) : ?>
							<span class="red">Canceled</span>
						<?php elseif($rs->Status == 3) : ?>
							<a href="javascript:void(0)" class="red" onclick="showMessage('<?php echo $rs->code; ?>')">Failed</a>
						<?php endif; ?>
					</td>
					<td class="middle text-center"><?php echo $rs->uname; ?></td>
					<td class="middle text-center"><?php echo $rs->SQNO; ?></td>
					<td class="middle text-center"><?php echo $rs->DocNum; ?></td>

				</tr>
				<?php $no++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Reject Reason</h4>
            </div>
            <div class="modal-body" style="padding-top:5px;">
            <div class="row">
              <div class="col-sm-12 col-xs-12" id="reason">

              </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="failedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:800px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom:0px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-size: 24px; font-weight: bold; padding-bottom: 10px; color:#428bca; border-bottom:solid 2px #428bca">Interface Status</h4>
            </div>
            <div class="modal-body" style="padding-top:5px;">
            <div class="row">
              <div class="col-sm-12 col-xs-12" id="failed-table">

              </div>
            </div>

        </div>
    </div>
  </div>
</div>

<script id="reason-template" type="text/x-handlebarsTemplate">
  <input type="hidden" id="U_WEBORDER" value="{{U_WEBORDER}}"/>
  <table class="table table-bordered" style="margin-bottom:0px;">
    <tbody style="font-size:16px;">
      <tr><td class="width-30">Web Order</td><td class="width-70">{{U_WEBORDER}}</td></tr>
      <tr><td class="width-30">Customer Code</td><td class="width-70">{{CardCode}}</td></tr>
      <tr><td>Customer Name</td><td>{{CardName}}</td></tr>
			<tr><td>Status</td><td class="red">Rejected</td></tr>
      <tr><td>Date/Time</td><td>{{date_upd}}</td></tr>
      <tr><td>Reason</td><td>{{Message}}</td></tr>
			<tr><td>Rejected By</td><td>{{rejected_by}}</td></tr>
    </tbody>
  </table>
</script>

<script id="failed-template" type="text/x-handlebarsTemplate">
  <input type="hidden" id="U_WEBORDER" value="{{U_WEBORDER}}"/>
  <table class="table table-bordered" style="margin-bottom:0px;">
    <tbody style="font-size:16px;">
      <tr><td class="width-30">Web Order</td><td class="width-70">{{U_WEBORDER}}</td></tr>
      <tr><td class="width-30">Customer Code</td><td class="width-70">{{CardCode}}</td></tr>
      <tr><td>Customer Name</td><td>{{CardName}}</td></tr>
      <tr><td>Date/Time To SAP</td><td>{{date_upd}}</td></tr>
      <tr><td>Status</td><td class="red">Failed</td></tr>
      <tr><td>Error message</td><td>{{Message}}</td></tr>
    </tbody>
  </table>
</script>

<script>
function showReason(reason) {
	$('#reason').text(reason);

	$('#reasonModal').modal('show');
}

	$('#user_id').select2();
	$('#sale_id').select2();
	$('#emp_id').select2();

</script>
<script src="<?php echo base_url(); ?>scripts/orders/order.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
