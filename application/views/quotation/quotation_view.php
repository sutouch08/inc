<?php $this->load->view('include/header'); ?>
<?php $pm = get_permission('SOODSO', $this->_user->uid, $this->_user->id_profile); ?>
<script src="<?php echo base_url(); ?>assets/js/jquery.autosize.js"></script>

<?php $this->load->view('quotation/view_style_sheet'); ?>

<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs padding-5">
    <h4 class="title">
      <?php echo $this->title; ?>
    </h4>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
    <h4 class="title-xs">
      <?php echo $this->title; ?>
    </h4>
  </div>
  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
  	<p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-default top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> &nbsp; Back</button>
			<?php if($this->pm->can_add) : ?>
				<button type="button" class="btn btn-xs btn-primary top-btn" onclick="duplicateSQ('<?php echo $order->code; ?>')"><i class="fa fa-copy"></i> Duplicate</button>
			<?php endif; ?>
			<?php if($pm->can_add && $order->Status == 1 && ($order->Approved == 'A' OR $order->Approved == 'S') ) : ?>
				<button type="button" class="btn btn-xs btn-success top-btn" onclick="createSO('<?php echo $order->code; ?>')"><i class="fa fa-copy"></i> Create Sale Order</button>
			<?php endif; ?>
			<?php if($this->pm->can_edit && $order->Status == -1) : ?>
				<button type="button" class="btn btn-xs btn-warning top-btn" onclick="goEdit('<?php echo $order->code; ?>')"><i class="fa fa-pencil"></i> Edit</button>
			<?php endif; ?>
			<?php if($order->Status != 2) : ?>
				<?php if($order->Approved == 'A' OR $order->Approved == 'S') : ?>
					<button type="button" class="btn btn-xs btn-info top-btn" onclick="printSQ()"><i class="fa fa-print"></i> Print</button>
				<?php else : ?>
					<button type="button" class="btn btn-xs btn-info top-btn" onclick="printSQ()">Preview</button>
				<?php endif; ?>
			<?php endif; ?>
			<?php if(empty($order->DocEntry) && ($order->Status == 3 OR $order->Status == 0 OR $this->_SuperAdmin) && ($order->Approved == 'A' OR $order->Approved == 'S')) : ?>
			<button type="button" class="btn btn-xs btn-success btn-100 top-btn" onclick="sendToSap('<?php echo $order->code; ?>')"><i class="fa fa-send"></i> Send to SAP</button>
			<?php endif; ?>
			<?php if($order->Status == 1 && ! empty($order->DocEntry) && !empty($order->DocNum)) : ?>
				<!--<button type="button" class="btn btn-xs btn-danger top-btn" onclick="cancleSap('<?php echo $order->code; ?>')">Cancel On SAP</button> -->
			<?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('quotation/quotation_view_header'); ?>
<?php $this->load->view('quotation/quotation_view_detail'); ?>
<?php $this->load->view('quotation/quotation_view_footer'); ?>
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


<script src="<?php echo base_url(); ?>scripts/quotation/quotation.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_add.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/quotation/quotation_view.js?v=<?php echo date('YmdH'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/address.js"></script>




<?php $this->load->view('include/footer'); ?>
