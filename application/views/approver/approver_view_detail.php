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
    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Username</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" class="width-100" value="<?php echo $this->user_model->get_uname($approver->user_id); ?>" disabled/>
    </div>
  </div>

	<div class="divider-hidden">	</div>

	<div class="form-group">
    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Approval</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 table-responsive">
			<table class="table table-striped table-bordered border-1" style="margin-bottom:0px;">
				<thead>
					<tr>
						<th class="fix-width-100">Document</th>
						<th class="fix-width-40 text-center">Review</th>
						<th class="fix-width-40 text-center">Approve</th>
						<th class="fix-width-100 text-center">Min Disc(%)</th>
						<th class="fix-width-100 text-center">Max Disc(%)</th>
						<th class="fix-width-120 text-center">Min Amount</th>
						<th class="fix-width-120 text-center">Max Amount</th>
					</tr>
				</thead>
				<tbody>
		<?php if(!empty($docType)) : ?>
			<?php foreach($docType as $rs) : ?>
				<?php $reChk = empty($rules[$rs->code]['review']) ? "" : is_active('1', $rules[$rs->code]['review']); ?>
				<?php $apChk = empty($rules[$rs->code]['approve']) ? "" : is_active('1', $rules[$rs->code]['approve']); ?>
				<?php $disabled = empty($rules[$rs->code]['approve']) ? "disabled" : ""; ?>
				<?php $minDisc = empty($rules[$rs->code]['minDisc']) ? 0.00 : $rules[$rs->code]['minDisc']; ?>
				<?php $maxDisc = empty($rules[$rs->code]['maxDisc']) ? 0.00 : $rules[$rs->code]['maxDisc']; ?>
				<?php $minAmount = empty($rules[$rs->code]['minAmount']) ? 0.00 : $rules[$rs->code]['minAmount']; ?>
				<?php $maxAmount = empty($rules[$rs->code]['maxAmount']) ? 0.00 : $rules[$rs->code]['maxAmount']; ?>
				<tr>
					<td><?php echo $rs->name; ?></td>
					<td class="text-center"><?php echo $reChk; ?></td>
					<td class="text-center"><?php echo $apChk; ?></td>
					<td class="text-center"><?php echo $minDisc; ?></td>
					<td class="text-center"><?php echo $maxDisc; ?></td>
					<td class="text-center"><?php echo number($minAmount, 2); ?></td>
					<td class="text-center"><?php echo number($maxAmount, 2); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
				</tbody>
			</table>
    </div>
  </div>

<!--
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label hidden-xs"></label>
    <div class="col-lg-6 col-md-7 col-sm-8 col-xs-12">
			<span class="blue">* Max Disc(%)</span> : ส่วนลดสูงสุดที่สามารถอนุมัติได้ (ส่วนต่างของส่วนลดที่ระบบกำหนดไว้ ้)
			เช่น ถ้าระบบให้ส่วนลด 50% แต่ส่วนลดถูกแก้ไขเป็น 60% ส่วนต่างของส่วนลดจะเท่ากับ 10% ทั้งนี้ส่วนต่างของส่วนลดนี้คำนวนจาก ราคา ส่วนลดรายการ ส่วนลดท้ายบิล
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label hidden-xs"></label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<span class="blue">* Max Amount</span> : มูลค่าสูงสุดที่สามารอนุมัติได้ โดยคำนวนจากมูลค่ารวมหลังส่วนลดท้ายบิล
    </div>
  </div>
-->
	<div class="divider-hidden"></div>

	<div class="form-group">
    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"> Status</label>
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label" style="text-align:left; padding-left:15px;">
			<?php echo ($approver->status == 1 ? '<i class="fa fa-check green"></i>  Active' : '<i class="fa fa-times red"></i>  Inactive'); ?>
    </label>
  </div>


	<div class="divider-hidden"></div>

</form>
<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
