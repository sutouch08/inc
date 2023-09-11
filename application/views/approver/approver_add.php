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
			<select class="width-100" id="user">
				<option value="">Please Select</option>
				<?php echo select_user(); ?>
			</select>
    </div>
		<div class="col-xs-12 col-sm-reset inline red margin-top-5" id="user-error"></div>
  </div>

	<div class="divider-hidden">	</div>

	<div class="form-group">
    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Approval</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 table-responsive">
			<table class="table table-striped border-1" style="margin-bottom:0px;">
				<thead>
					<tr>
						<th class="fix-width-100">Document</th>
						<th class="fix-width-40 text-center">Review</th>
						<th class="fix-width-40 text-center">Approve</th>
						<th class="fix-width-100">Min Disc(%)</th>
						<th class="fix-width-100">Max Disc(%)</th>
						<th class="fix-width-120">Min Amount</th>
						<th class="fix-width-120">Max Amount</th>
					</tr>
				</thead>
				<tbody>
		<?php if(!empty($docType)) : ?>
			<?php foreach($docType as $rs) : ?>
				<tr>
					<td>
						<input type="hidden" class="docType" value="<?php echo $rs->code; ?>" />
						<?php echo $rs->name; ?>
					</td>
					<td class="text-center">
						<label>
							<input type="checkbox" class="ace chk review-<?php echo $rs->code; ?>"
							id="review-<?php echo $rs->code; ?>"
							onchange="toggleReview('<?php echo $rs->code; ?>')" />
							<span class="lbl"></span>
						</label>
					</td>
					<td class="text-center">
						<label>
							<input type="checkbox" class="ace chk approve-<?php echo $rs->code; ?>"
							id="approve-<?php echo $rs->code; ?>"
							onchange="toggleAproveDoc('<?php echo $rs->code; ?>')"/>
							<span class="lbl"></span>
						</label>
					</td>
					<td>
						<input type="number" class="form-control input-sm text-center disc" id="min-disc-<?php echo $rs->code; ?>" value="0.00" disabled />
					</td>
					<td>
						<input type="number" class="form-control input-sm text-center disc disc-max" id="max-disc-<?php echo $rs->code; ?>" value="0.00" disabled/>
					</td>
					<td>
						<input type="text" class="form-control input-sm text-center amount"	id="min-amount-<?php echo $rs->code; ?>" value="0.00" disabled/>
					</td>
					<td>
						<input type="text" class="form-control input-sm text-center amount" id="max-amount-<?php echo $rs->code; ?>" value="0.00" disabled/>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
				</tbody>
			</table>
    </div>
  </div>
<!--
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label" style="padding-top:0px;">*</label>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
			<span class="blue">Max Disc(%)</span> : ส่วนลดสูงสุดที่สามารถอนุมัติได้ (ส่วนต่างของส่วนลดที่ระบบกำหนดไว้ ้)
			เช่น ถ้าระบบให้ส่วนลด 50% แต่ส่วนลดถูกแก้ไขเป็น 60% ส่วนต่างของส่วนลดจะเท่ากับ 10% ทั้งนี้ส่วนต่างของส่วนลดนี้คำนวนจาก ราคา ส่วนลดรายการ ส่วนลดท้ายบิล
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label" style="padding-top:0px;">*</label>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<span class="blue">Max Amount</span> : มูลค่าสูงสุดที่สามารอนุมัติได้ โดยคำนวนจากมูลค่ารวมหลังส่วนลดท้ายบิล
    </div>
  </div>
-->
	<div class="divider-hidden"></div>

	<div class="form-group">
    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label>
				<input type="checkbox" class="ace" id="status" checked />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>


	<div class="divider-hidden"></div>


  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
			<?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success btn-100" onclick="saveAdd()">Add</button>
			<?php endif; ?>
    </div>
  </div>
</form>

<script>
	$('#user').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('YmdH'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
