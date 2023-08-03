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
			<button type="button" class="btn btn-xs btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Web No.</label>
    <input type="text" class="width-100 search-box" name="code"  value="<?php echo $code; ?>" />
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Customer</label>
		<input type="text" class="width-100 search-box" name="customer" value="<?php echo $customer; ?>" />
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>SQ No.</label>
    <input type="text" class="width-100 search-box" name="sqNo"  value="<?php echo $sqNo; ?>" />
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>User</label>
    <select class="width-100 filter" name="user_id" id="user_id">
			<option value="all">ทั้งหมด</option>
			<?php echo select_user($user_id); ?>
		</select>
	</div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Channels</label>
		<select class="width-100 filter" name="channels" id="channels">
			<option value="all">ทั้งหมด</option>
			<?php echo select_channels($channels); ?>
		</select>
  </div>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Payment</label>
		<select class="width-100 filter" name="payment" id="payment">
			<option value="all">ทั้งหมด</option>
			<?php echo select_payment_term($payment); ?>
		</select>
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Order Role</label>
		<select class="width-100 filter" name="role" id="role">
			<option value="all">ทั้งหมด</option>
			<option value="S" <?php echo is_selected($role, 'S'); ?>>BEC</option>
			<option value="C" <?php echo is_selected($role, 'C'); ?>>Customer</option>
		</select>
  </div>
	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label>Sale Employee</label>
		<select class="width-100 filter" name="sale_id" id="sale_id">
			<option value="all">ทั้งหมด</option>
			<?php echo select_sale($sale_id); ?>
		</select>
  </div>


	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
		<label>Approval</label>
		<select class="width-100 filter" name="approval" id="approval">
			<option value="all">ทั้งหมด</option>
			<option value="P" <?php echo is_selected('P', $approval); ?>>Pending</option>
			<option value="A" <?php echo is_selected('A', $approval); ?>>Approved</option>
			<option value="R" <?php echo is_selected('R', $approval); ?>>Rejected</option>
			<option value="S" <?php echo is_selected('S', $approval); ?>>Approvaless</option>
		</select>
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>สถานะ</label>
		<select class="width-100 filter" name="status" id="status">
			<option value="all">ทั้งหมด</option>
			<option value="-1" <?php echo is_selected('-1', $status); ?>>Draft</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>Pending</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>Success</option>
			<option value="2" <?php echo is_selected('2', $status); ?>>Canceled</option>
			<option value="3" <?php echo is_selected('3', $status); ?>>Failed</option>
		</select>
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>Document date</label>
		<div class="input-daterange input-group width-100">
			<input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
			<input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
		</div>
	</div>

	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 padding-5">
		<label class="display-block not-show">onlyMe</label>
		<button type="button"
		class="hidden-sm btn btn-xs btn-block <?php echo ($onlyMe == 1 ? "btn-info" : ''); ?>"
		onclick="toggleOnlyMe()">Only Me</button>
		<button type="button"
		class="visible-sm btn btn-xs btn-block <?php echo ($onlyMe == 1 ? "btn-info" : ''); ?>"
		onclick="toggleOnlyMe()">Me</button>
	</div>

	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 padding-5">
		<label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()" >Search</button>
	</div>
	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 padding-5">
		<label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-15 text-center">
		<label>
			<input type="checkbox" class="ace" onchange="toggleShipDate($(this))" <?php echo is_checked($chk_ship, '1'); ?> />
			<span class="lbl">Shipment Date</span>
			<input type="hidden" name="chk_ship" id="chk-ship" value="<?php echo $chk_ship; ?>"/>
		</label>
		<label class="margin-left-15">
			<input type="checkbox" class="ace" onchange="toggleSqNo($(this))" <?php echo is_checked($chk_sqNo, '1'); ?> />
			<span class="lbl">SQ No.</span>
			<input type="hidden" name="chk_sqNo" id="chk-sqNo" value="<?php echo $chk_sqNo; ?>"/>
		</label>
		<label class="margin-left-15">
			<input type="checkbox" class="ace" onchange="toggleChannels($(this))" <?php echo is_checked($chk_channels, '1'); ?> />
			<span class="lbl">Channels</span>
			<input type="hidden" name="chk_channels" id="chk-channels" value="<?php echo $chk_channels; ?>"/>
		</label>
		<label class="margin-left-15">
			<input type="checkbox" class="ace" onchange="togglePayment($(this))" <?php echo is_checked($chk_payment, '1'); ?>/>
			<span class="lbl">Payment</span>
			<input type="hidden" name="chk_payment" id="chk-payment" value="<?php echo $chk_payment; ?>"/>
		</label>
	</div>
</div>

<input type="hidden" id="onlyMe" name="onlyMe" value="<?php echo $onlyMe; ?>" />
</form>
<hr class="padding-5 "/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover dataTable border-1" style="min-width:1020px;">
			<thead>
				<tr style="font-size:10px;">
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-80 middle text-center">Document date</th>
					<th class="fix-width-80 middle text-center shipDate <?php echo ($chk_ship == 1 ? '' : 'hide'); ?>">Shipment date</th>
					<th class="fix-width-120 min-width-120 middle">Web No.</th>
					<th class="fix-width-120 middle sqNo <?php echo ($chk_sqNo == 1 ? '' : 'hide'); ?>">SQ No.</th>
					<th class="fix-width-100 middle">Customer code</th>
					<th class="min-width-200 middle">Customer name</th>
					<th class="fix-width-100 middle text-right">Amount</th>
					<th class="fix-width-100 middle channels <?php echo ($chk_channels == 1 ? '' : 'hide'); ?>">Channels</th>
					<th class="fix-width-80 middle payment <?php echo ($chk_payment == 1 ? '' : 'hide'); ?>">Payment</th>
					<th class="fix-width-80 middle text-center">Approval</th>
					<th class="fix-width-80 middle text-center">Status</th>
					<th class="fix-width-100 middle text-center user">User</th>
					<th class="min-width-120 middle"></th>
				</tr>
			</thead>
			<tbody style="font-size:12px;">
		<?php if( ! empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<tr>
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle text-center"><?php echo thai_date($rs->DocDate, FALSE, '.'); ?></td>
					<td class="middle text-center shipDate <?php echo ($chk_ship == 1 ? '' : 'hide'); ?>"><?php echo thai_date($rs->DocDueDate, FALSE, '.'); ?></td>
					<td class="middle"><?php echo $rs->code; ?></td>
					<td class="middle sqNo <?php echo ($chk_sqNo == 1 ? '' : 'hide'); ?>"><?php echo $rs->SqNo; ?></td>
					<td class="middle"><?php echo $rs->CardCode; ?></td>
					<td class="moddle"><?php echo $rs->CardName; ?></td>
					<td class="middle text-right"><?php echo number($rs->DocTotal, 2); ?></td>

					<td class="middle channels <?php echo ($chk_channels == 1 ? '' : 'hide'); ?>"><?php echo $rs->channels_name; ?></td>
					<td class="middle payment <?php echo ($chk_payment == 1 ? '' : 'hide'); ?>"><?php echo $rs->payment_name; ?></td>

					<td class="middle text-center">
						<?php if($rs->Status == 0 OR $rs->Status == 1) : ?>
							<?php if($rs->must_approve == 0) : ?>
								<span class="green">System</span>
							<?php else : ?>
								<?php if($rs->Approved == 'P') : ?>
									<span class="orange">Pending</span>
								<?php elseif($rs->Approved == 'A') : ?>
									<span class="green">Approved</span>
								<?php elseif($rs->Approved == 'R') : ?>
								<span class="red">Rejected</span>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<?php if($rs->Status == -1) : ?>
							<span class="purple">Draft</span>
						<?php elseif($rs->Status == 1) : ?>
							<span class="green">Success</span>
						<?php elseif($rs->Status == 2) : ?>
							<span class="red">Canceled</span>
						<?php elseif($rs->Status == 3) : ?>
							<a href="javascript:void(0)" class="red" onclick="showMessage('<?php echo $rs->code; ?>')">Failed</a>
						<?php else : ?>
							<span class="orange">Pending</span>
						<?php endif; ?>
					</td>
					<td class="middle text-center"><?php echo $rs->uname; ?></td>
					<td class="middle text-right">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
					<?php if($this->pm->can_edit && ($rs->Status == 0 OR $rs->Status == -1 OR $rs->Status == 3)) : ?>
						<button type="button" class="btn btn-mini btn-warning" onclick="edit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
					<?php endif; ?>
					<?php if($this->pm->can_delete && $rs->Status != 1 && $rs->Status != 2) : ?>
						<button type="button" class="btn btn-mini btn-danger" onclick="cancleOrder('<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
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
<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order.js?v=<?php echo date('Ymd'); ?>"></script>
<script>

function toggleShipDate(el) {
	if(el.is(':checked')) {
		$('.shipDate').removeClass('hide');
		$('#chk-ship').val(1);
	}
	else {
		$('.shipDate').addClass('hide');
		$('#chk-ship').val(0);
	}

	getSearch();
}

	function toggleSqNo(el) {
		if(el.is(':checked')) {
			$('.sqNo').removeClass('hide');
			$('#chk-sqNo').val(1);
		}
		else {
			$('.sqNo').addClass('hide');
			$('#chk-sqNo').val(0);
		}

		getSearch();
	}

	function toggleChannels(el) {
		if(el.is(':checked')) {
			$('.channels').removeClass('hide');
			$('#chk-channels').val(1);
		}
		else {
			$('.channels').addClass('hide');
			$('#chk-channels').val(0);
		}

		getSearch();
	}

	function togglePayment(el) {
		if(el.is(':checked')) {
			$('.payment').removeClass('hide');
			$('#chk-payment').val(1);
		}
		else {
			$('.payment').addClass('hide');
			$('#chk-payment').val(0);
		}

		getSearch();
	}

	$('#sale_id').select2();
	$('#user_id').select2();
	$('#channels').select2();
	$('#payment').select2();
	$('#approval').select2();
	$('#status').select2();
</script>

<?php $this->load->view('include/footer'); ?>
