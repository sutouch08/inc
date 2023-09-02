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
<form id="addForm" method="post" action="<?php echo $this->home; ?>/add">
	<?php $this->load->view('quotation/quotation_header'); ?>
	<?php $this->load->view('quotation/quotation_detail'); ?>
	<?php $this->load->view('quotation/quotation_footer'); ?>

	<input type="hidden" id="sale_id" value="<?php echo $this->_user->sale_id; ?>" />
	<input type="hidden" id="sale_team" value="<?php echo $this->_user->team_id; ?>" />
	<input type="hidden" id="vat_rate" value="<?php echo getConfig('SALE_VAT_RATE'); //--- default sale vat rate ?>" />
	<input type="hidden" id="vat_code" value="<?php echo getConfig('SALE_VAT_CODE'); //--- default sale vat code?>" />
	<input type="hidden" id="priceList" value="1" />
	<input type="hidden" id="is_draft" value="0">
	<input type="hidden" id="max_amount" value="<?php echo getConfig('AMOUNT_TO_APPROVE_SQ'); ?>" />
	<input type="hidden" id="max_discount" value="<?php echo getConfig('DISCOUNT_TO_APPROVE_SQ'); ?>" />
</form>

<?php $this->load->view('quotation/quotation_ship_to_modal'); ?>
<?php $this->load->view('quotation/quotation_bill_to_modal'); ?>


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


<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>
