<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardCode" class="form-control input-sm" value=""/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Name</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="CardName" class="form-control input-sm" value="" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Contact</label>
      <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
        <input type="text" id="contact" class="form-control input-sm" maxlength="100" value="" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Phone No</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <input type="text" id="phone" class="form-control input-sm" maxlength="50" value="" />
      </div>
    </div>

		<div class="form-group">
			<label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Payment</label>
			<div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
				<select class="form-control input-sm" id="payment" name="payment">
					<?php echo select_payment_term(); ?>
				</select>
			</div>
		</div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right">Bill To</label>
      <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
        <select class="form-control input-sm" id="billToCode">
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label no-padding-right"></label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <textarea id="BillTo" class="autosize autosize-transition form-control"></textarea>
      </div>
    </div>




  </div>
</div>
