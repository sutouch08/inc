<?php
		$limitOn = $CREDIT_LIMIT == 1 ? 'btn-primary' : '';
		$limitOff = $CREDIT_LIMIT == 0 ? 'btn-primary' : '';		
?>

  <form id="orderForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Credit Limit</span></div>
    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $limitOn; ?>" style="width:50%;" id="btn-limit-on" onClick="toggleCreditLimit(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $limitOff; ?>" style="width:50%;" id="btn-limit-off" onClick="toggleCreditLimit(0)">ปิด</button>
      </div>
      <span class="help-block">เมื่อเปิดใช้งาน จะไม่อนุญาติให้สั่งซื้อสินค้าเกินกว่าเครดิตคงเหลือได้</span>
      <input type="hidden" name="CREDIT_LIMIT" id="credit-limit" value="<?php echo $CREDIT_LIMIT; ?>" />
    </div>
	</div>

    <div class="divider-hidden"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>
	<div class="row">
	      <div class="col-lg-9 col-md-9 col-sm-9 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 hidden-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('orderForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
			<div class="col-xs-12 text-center visible-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('orderForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
