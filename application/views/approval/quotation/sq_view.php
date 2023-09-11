<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>

<?php $this->load->view('approval/quotation/view_style_sheet'); ?>

<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5">
    <h4 class="title">
      <?php echo $this->title; ?>
    </h4>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
    	<p class="pull-right top-p">
        <button type="button" class="btn btn-xs btn-default btn-100 top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('approval/quotation/sq_header'); ?>
<?php $this->load->view('approval/quotation/sq_detail'); ?>
<?php $this->load->view('approval/quotation/sq_footer'); ?>
<?php $this->load->view('quotation/quotation_logs_modal'); ?>
<?php $this->load->view('quotation/reject_modal'); ?>

<input type="hidden" id="code" value="<?php echo $order->code; ?>" />
<input type="hidden" id="sale_id" value="<?php echo $order->SlpCode; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname;?>" />
<input type="hidden" id="id" value="<?php echo $order->id; ?>" />
<input type="hidden" id="discDiff" value="<?php echo $order->disc_diff; ?>" />


<script src="<?php echo base_url(); ?>scripts/approval/quotation_approval.js?v=<?php echo date('YmdH'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
