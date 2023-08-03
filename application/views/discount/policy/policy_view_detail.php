<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
  	<p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		<?php if($this->pm->can_edit) : ?>
			<button type="button" class="btn btn-xs btn-primary" onclick="goEdit(<?php echo $policy->id; ?>)"><i class="fa fa-pencil"></i> Edit</button>
		<?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('discount/policy/policy_view_header'); ?>
<?php $this->load->view('discount/policy/policy_rule_list', array('view_detail' => 'Y')); ?>


<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_list.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
