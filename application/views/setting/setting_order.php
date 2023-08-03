<?php
		$limitOn = $CREDIT_LIMIT == 1 ? 'btn-primary' : '';
		$limitOff = $CREDIT_LIMIT == 0 ? 'btn-primary' : '';
		$stockOn = $GET_STOCK_ON_CUSTOMER_ORDER == 1 ? 'btn-primary' : '';
		$stockOff = $GET_STOCK_ON_CUSTOMER_ORDER == 0 ? 'btn-primary' : '';
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
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Show Available Stock To Customer</span></div>
		<div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
			<div class="btn-group input-medium">
				<button type="button" class="btn btn-sm <?php echo $stockOn; ?>" style="width:50%;" id="btn-stock-on" onClick="toggleStock(1)">เปิด</button>
				<button type="button" class="btn btn-sm <?php echo $stockOff; ?>" style="width:50%;" id="btn-stock-off" onClick="toggleStock(0)">ปิด</button>
			</div>
			<span class="help-block">เมื่อเปิดใช้งาน ระบบจะดึงสต็อกคงเหลือมาคำนวนยอด Avalible ทำให้ระบบแสดงผลช้าลงเป็นอย่างมาก</span>
			<input type="hidden" name="GET_STOCK_ON_CUSTOMER_ORDER" id="available-stock" value="<?php echo $GET_STOCK_ON_CUSTOMER_ORDER; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Customer order limit SKU per order</span></div>
		<div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
			<input type="number" class="form-control  input-small text-center" id="CUSTOMER_ORDER_LIMIT_SKU" name="CUSTOMER_ORDER_LIMIT_SKU" value="<?php echo $CUSTOMER_ORDER_LIMIT_SKU; ?>" />
			<span class="help-block">จำกัดจำนวน SKU สูงสุด / 1 ออเดอร์ หากเกินกว่าที่กำหนดจะสร้างส่วนที่เกินเป็น ออเดอร์ใหม่ (เฉพาะ C-user) หากไม่ใช้งานให้กำหนดเป็น  0</span>
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
