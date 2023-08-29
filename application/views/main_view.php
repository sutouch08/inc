<?php $this->load->view('include/header'); ?>
	<div class="row">
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center">
	    <h1>Hello! <?php echo get_cookie('displayName'); ?></h1>
	    <h5>Good to see you here</h5>
	  </div>
	  <div class="divider-hidden"></div>
		<div class="divider"></div>
	</div>
	<div class="row">
		<?php if($is_approver) : ?>
			<?php if( ! empty($aps)) : ?>
				<?php foreach($aps as $ap) : ?>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
						<div class="item-box text-center font-size-18" style="border-radius:10px;">
							<?php echo $ap->doc_name; ?>
							<?php $right = $ap->review ? 'review' : 'approve'; ?>
							<span id="count-sq" class="badge badge-info display-block margin-top-10 font-size-18 pointer" style="padding:10px;">
								<?php echo $ap->count; ?>
							</span>
						</div>

						<input type="hidden" id="<?php echo $ap->docType; ?>"
						value="<?php echo $right; ?>"
						data-disc="<?php echo $ap->maxDisc; ?>" data-amount="<?php echo $ap->maxAmount; ?>" />
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>


<input type="hidden" id="is_approver" value="<?php echo $is_approver; ?>" />

<?php if($is_approver) : ?>
	<script src="<?php echo base_url(); ?>scripts/count_doc.js?v=<?php echo date('Ymd'); ?>"></script>
<?php endif; ?>
<?php $this->load->view('include/footer'); ?>
