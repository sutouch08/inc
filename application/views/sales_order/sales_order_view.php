<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>
<style>
	.form-group {
		margin-bottom: 5px;
	}
	.input-icon > .ace-icon {
		z-index: 1;
	}

	.bg-grey {
		background-color: #e7e7e7;
	}
</style>
<div class="row">
	<div class="col-sm-6 col-xs-6 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
        <button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
				<?php if($this->pm->can_add) : ?>
					<button type="button" class="btn btn-xs btn-primary" onclick="duplicateSO('<?php echo $order->code; ?>')"><i class="fa fa-copy"></i> Duplicate</button>
				<?php endif; ?>
				<?php if(($this->pm->can_add OR $this->pm->can_edit) && empty($order->DocEntry) && ($order->Status == 3 OR $order->Status == 0 OR $order->Status == 1) && ($order->Approved == 'A' OR $order->Approved == 'S')) : ?>
				<button type="button" class="btn btn-xs btn-success" onclick="sendToSap('<?php echo $order->code; ?>')"><i class="fa fa-send"></i> Send to SAP</button>
				<?php endif; ?>
				<?php if($this->pm->can_edit && $order->Status == 1 && ! empty($order->DocEntry) && !empty($order->DocNum)) : ?>
					<button type="button" class="btn btn-xs btn-danger" onclick="cancleSap('<?php echo $order->code; ?>')">Edit Request</button>
				<?php endif; ?>

				<?php if($this->_SuperAdmin) : ?>
					<button type="button" class="btn btn-xs btn-danger" onclick="dumpJson('<?php echo $order->code; ?>')">GET JSON</button>
				<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<?php
if($order->Status != 2 && $order->Approved == 'R')
{
	$this->load->view('reject_watermark');
}

if($order->Status == 2 )
{
	$this->load->view('cancle_watermark');
}
?>

<?php $this->load->view('sales_order/sales_order_view_header'); ?>
<?php $this->load->view('sales_order/sales_order_view_detail'); ?>
<?php $this->load->view('sales_order/sales_order_view_footer'); ?>

<input type="hidden" id="sale_id" value="<?php echo $order->SlpCode; ?>" />
<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname;?>" />




<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/sales_order/sales_order_add.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>
