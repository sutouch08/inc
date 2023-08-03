<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="colo-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="colo-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
  	<p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      <button type="button" class="btn btn-xs btn-primary" onclick="getActiveRuleList()">Add Discount Rule</button>
    <?php endif; ?>
    </p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>

<?php $this->load->view('discount/policy/policy_edit_header'); ?>
<?php $this->load->view('discount/policy/policy_rule_list'); ?>

<div class="modal fade" id="rule-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกเงื่อนไขส่วนลด</h4>
      </div>
      <div class="modal-body" id="rule-body">
        <div class="row">
          <div class="scrollbar-inner">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height:400px;" id="result">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" id="btn-add-rule" onclick="addRule()" disabled><i class="fa fa-plus"></i> Add To List</button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<script id="rule-template" type="text/x-handlebarsTemplate">
<table class="table table-striped tablesorter margin-bottom-0" id="myTable">
  <thead>
    <tr>
      <th class="fix-width-40">เลือก</th>
      <th class="fix-width-120">รหัส</th>
      <th class="min-width-250">เงื่อนไข</th>
    </tr>
  </thead>
  <tbody>
{{#each this}}
  {{#if nodata}}
    <tr>
      <td colspan="3" class="text-center">ไม่พบรายการ</td>
    </tr>
  {{else}}
    <tr class="font-size-12">
      <td class="text-center">
        <input type="checkbox" class="ace chk-rule" name="ruleId[{{id_rule}}]" id="ruleId_{{id_rule}}" value="{{id_rule}}" onchange="toggleButton()" />
				<span class="lbl"></span>
      </td>
      <td>
        <label for="ruleId_{{id_rule}}" class="padding-5">{{ruleCode}}</label>
      </td>
      <td>
        <label for="ruleId_{{id_rule}}" class="padding-5">{{ruleName}}</label>
      </td>
    </tr>
  {{/if}}
{{/each}}
  </tbody>
</table>
</script>

<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_list.js"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js"></script>

<?php $this->load->view('include/footer'); ?>
