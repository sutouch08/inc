<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-7 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5 padding-5">
    <p class="pull-right top-p">
			<?php if($this->pm->can_add) : ?>
      <button type="button" class="btn btn-xs btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
			<?php endif; ?>
    </p>
  </div>
</div>
<hr class="padding-5"/>

<form id="searchForm" method="post" >
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Document No.</label>
    <input type="text" class="form-control input-sm text-center search-box" name="code" value="<?php echo $code; ?>" autofocus />
  </div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm text-center search-box" name="name" value="<?php echo $name; ?>" />
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Promotion No.</label>
    <input type="text" class="form-control input-sm text-center search-box" name="policy" value="<?php echo $policy; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm filter" name="active" id="active">
      <option value="all" <?php echo is_selected("all", $active); ?>>ทั้งหมด</option>
      <option value="1" <?php echo is_selected('1', $active); ?>>Active</option>
      <option value="0" <?php echo is_selected('0', $active); ?>>Inactive</option>
    </select>
  </div>

	<div class="col-xs-6 visible-xs padding-5">
		&nbsp;
	</div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">search</label>
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">reset</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>

<hr class="padding-5"/>
<?php echo $this->pagination->create_links(); ?>
 <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
     <table class="table table-striped border-1 min-width-950">
       <thead>
         <tr>
           <th class="fix-width-60 text-center">#</th>
           <th class="fix-width-120 text-center">Document No.</th>
           <th class="min-width-250">Description</th>
					 <th class="fix-width-100 text-center">Type</th>
           <th class="fix-width-120 text-center">Promotion No.</th>
           <th class="fix-width-150 text-center">Discount</th>
           <th class="fix-width-60 text-center">Status</th>
           <th class="fix-width-150"></th>
         </tr>
       </thead>
       <tbody>
<?php if(!empty($data)) : ?>
  <?php $no = $this->uri->segment($this->segment) + 1; ?>
  <?php foreach($data as $rs) : ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center no"><?php echo number($no); ?></td>
          <td class="middle text-center"><?php echo $rs->code; ?></td>
          <td class="middle"><?php echo $rs->name; ?></td>
					<td class="middle text-center">
						<?php echo ($rs->type == 'N' ? 'Net price': 'Percentage'); ?>
					</td>
          <td class="middle text-center"><?php echo $rs->policy_code; ?></td>
          <td class="middle text-center">
						<?php echo discount_label($rs->type, $rs->price, $rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5); ?>
					</td>
          <td class="middle text-center"><?php echo is_active($rs->active); ?></td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
      <?php if($this->pm->can_edit) : ?>
            <button type="button" class="btn btn-xs btn-warning" onclick="goEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
      <?php endif; ?>
      <?php if($this->pm->can_delete) : ?>
            <button type="button" class="btn btn-xs btn-danger" onclick="getDelete('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
      <?php endif; ?>

          </td>
        </tr>
    <?php $no++; ?>
  <?php endforeach; ?>

<?php else : ?>
        <tr>
          <td colspan="7" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
<?php endif; ?>
       </tbody>
     </table>
   </div>
 </div>

<script src="<?php echo base_url(); ?>scripts/discount/rule/rule.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
