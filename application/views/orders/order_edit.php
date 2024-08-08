<?php $this->load->view('include/header'); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>

<style>
	.form-group {
		margin-bottom: 5px;
	}
	.input-icon > .ace-icon {
		z-index: 1;
	}
</style>
<div class="row">
	<div class="col-sm-6 col-xs-6 padding-5">
    <h4 class="title">
      <?php echo $this->title; ?>
    </h4>
    </div>
    <div class="col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
        <button type="button" class="btn btn-xs btn-default" onclick="leave()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('orders/order_header'); ?>
<?php $this->load->view('orders/order_detail'); ?>
<?php $this->load->view('orders/order_footer'); ?>

<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname;?>" />
<input type="hidden" id="sale_team" value="<?php echo $order->sale_team; ?>" />
<input type="hidden" id="is_draft" value="0">
<input type="hidden" id="max_amount" value="<?php echo getConfig('AMOUNT_TO_APPROVE_SQ'); ?>" />
<input type="hidden" id="max_discount" value="<?php echo getConfig('DISCOUNT_TO_APPROVE_SQ'); ?>" />


<?php $this->load->view('orders/order_ship_to_modal'); ?>
<?php $this->load->view('orders/order_bill_to_modal'); ?>


<script id="ship-to-template" type="text/x-handlebarsTemplate">
		{{#each this}}
			<option value="{{code}}" data-name="{{name}}">{{code}} : {{name}}</option>
		{{/each}}
</script>

<script id="bill-to-template" type="text/x-handlebarsTemplate">
		{{#each this}}
			<option value="{{code}}" data-name="{{name}}">{{code}} : {{name}}</option>
		{{/each}}
</script>

<script src="<?php echo base_url(); ?>scripts/orders/order.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_add.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_edit.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>

<?php $this->load->view('include/footer'); ?>
