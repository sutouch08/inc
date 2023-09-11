<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title"> <?php echo $this->title; ?> </h3>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Web No.</label>
    <input type="text" class="width-100 search-box" name="code"  value="<?php echo $code; ?>" />
  </div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Customer</label>
		<input type="text" class="width-100 search-box" name="customer" value="<?php echo $customer; ?>" />
	</div>

	<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Project</label>
		<input type="text" class="width-100 search-box" name="project" value="<?php echo $project; ?>" />
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

<input type="hidden" name="search" value="1" />
</form>
<hr class="padding-5 margin-top-10"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive" id="double-scroll">
		<table class="table table-striped table-hover dataTable border-1" style="margin-bottom:10px; min-width:970px; border-collapse:inherit;">
			<thead>
				<tr style="font-size:10px;">
					<th class="fix-width-60 text-center"></th>
					<th class="fix-width-60 middle text-center">#</th>
					<th class="fix-width-80 middle">Date</th>
					<th class="fix-width-100 middle">Web No.</th>
					<th class="fix-width-100 middle">Customer code</th>
					<th class="min-width-250 middle">Customer name</th>
					<th class="fix-width-120 middle">Project</th>
					<th class="fix-width-100 middle text-right">Amount</th>
					<th class="fix-width-100 middle text-center">User</th>
				</tr>
			</thead>
			<tbody style="font-size:12px;">
		<?php if( ! empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<tr>
					<td class="middle">
						<button type="button" class="btn btn-mini btn-info" onclick="viewDetail('<?php echo $rs->code; ?>')">Preview</button>
					</td>
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo thai_date($rs->DocDate, FALSE); ?></td>
					<td class="middle"><?php echo $rs->code; ?></td>
					<td class="middle"><?php echo $rs->CardCode; ?></td>
					<td class="middle"><?php echo $rs->CardName; ?></td>
					<td class="middle"><?php echo $rs->Project; ?></td>
					<td class="middle text-right"><?php echo number($rs->DocTotal, 2); ?></td>
					<td class="middle text-center"><?php echo $rs->uname; ?></td>
				</tr>
				<?php $no++; ?>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="9" class="middle text-center"><h3>--- No data found ---</h3></td>
			</tr>
		<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/approval/quotation_approval.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
