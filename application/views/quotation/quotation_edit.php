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
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6 col-xs-6 padding-5">
    	<p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-default" onclick="leave()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
      </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('quotation/quotation_edit_header'); ?>
<?php $this->load->view('quotation/quotation_edit_detail'); ?>
<?php $this->load->view('quotation/quotation_edit_footer'); ?>

<input type="hidden" id="vat_rate" value="<?php echo $order->VatRate; ?>" />
<input type="hidden" id="vat_code" value="<?php echo $order->VatGroup; ?>" />
<input type="hidden" id="priceList" value="<?php echo $order->PriceList; ?>" />
<input type="hidden" id="user_id" value="<?php echo $order->user_id; ?>" />
<input type="hidden" id="uname" value="<?php echo $order->uname;?>" />
<input type="hidden" id="sale_team" value="<?php echo $order->sale_team; ?>" />
<input type="hidden" id="is_draft" value="0">


<?php $this->load->view('quotation/quotation_ship_to_modal'); ?>
<?php $this->load->view('quotation/quotation_bill_to_modal'); ?>


<script id="ship-to-template" type="text/x-handlebarsTemplate">
		{{#each this}}
			<option value="{{code}}">{{code}}</option>
		{{/each}}
</script>

<script id="bill-to-template" type="text/x-handlebarsTemplate">
		{{#each this}}
			<option value="{{code}}">{{code}}</option>
		{{/each}}
</script>


<script id="series-template" type="text/x-handlebarsTemplate">
	{{#each this}}
		<option value="{{code}}" {{is_selected}}>{{name}}</option>
	{{/each}}
</script>


<!--  Add New Address Modal  --------->
<div class="modal fade" id="free-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:800px;">
        <div class="modal-content">
            <div class="modal-body">
            <div class="row">
                <table class="table table-striped broder-1">
									<tbody id="free-item-list">

									</tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>




<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>
