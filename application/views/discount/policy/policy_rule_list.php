
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-bordered border-1 min-width-800">
      <thead>
        <tr>
          <th rowspan="2" class="fix-width-60 middle text-center">#</th>
          <th rowspan="2" class="fix-width-120 middle text-center">Code</th>
          <th rowspan="2" class="min-width-250 middle text-center">Description</th>
					<th rowspan="2" class="fix-width-100 middle text-center">Type</th>
          <th rowspan="2" class="fix-width-150 middle text-center">Discount</th>
					<th rowspan="2" class="fix-width-80 middle text-center">Free</th>
          <th colspan="7" class="middle text-center">Conditions</th>
        </tr>
        <tr class="font-size-10">
          <th class="fix-width-80 text-center">Customer</th>
          <th class="fix-width-80 text-center">Product</th>
          <th class="fix-width-80 text-center">Channels</th>
          <th class="fix-width-80 text-center">Payment</th>
          <th class="fix-width-80 text-center">Min Qty.</th>
					<th class="fix-width-80 text-center">Min Amount.</th>
          <th class="fix-width-100"></th>
        </tr>
      </thead>
      <tbody>
<?php if(!empty($rules)) : ?>
  <?php $no = 1; ?>
  <?php foreach ($rules as $rs) : ?>
        <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $rs->code; ?></td>
          <td class="middle"><?php echo $rs->name; ?></td>
					<td class="middle text-center"><?php echo ($rs->type == 'N' ? 'Net Price' : 'Discount'); ?></td>
          <td class="middle text-center"><?php echo discount_label($rs->type, $rs->price, $rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5); ?></td>
					<td class="middle text-center"><?php echo $rs->freeQty; ?></td>
          <td class="middle text-center"><?php echo ($rs->all_customer == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_product == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_channels == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
          <td class="middle text-center"><?php echo ($rs->all_payment == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
          <td class="middle text-center"><?php echo (empty($rs->minQty) ? "No" : number($rs->minQty)); ?></td>
					<td class="middle text-center"><?php echo (empty($rs->minAmount) ? "No" : number($rs->minAmount, 2)); ?></td>
          <td class="middle text-right">
            <?php if(empty($view_detail)) : ?>
            <button type="button" class="btn btn-minier btn-info" onclick="viewRuleDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
            <?php if($this->pm->can_edit) : ?>
            <button type="button" class="btn btn-minier btn-danger" onclick="unlinkRule(<?php echo $rs->id; ?>, '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
          <?php endif; ?>
          </td>
        </tr>
  <?php   $no++; ?>
  <?php endforeach; ?>

<?php else : ?>
      <tr>
        <td colspan="13" class="text-center">
          <h4>ไม่พบรายการ</h4>
        </td>
      </tr>

<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
