<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
        <input type="text" id="CardCode" class="form-control input-xs" value="<?php echo $order->CardCode; ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Name</label>
      <div class="col-lg-7 col-md-7-harf col-sm-8-harf col-xs-8">
        <input type="text" id="CardName" class="form-control input-xs" value="<?php echo $order->CardName; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Contact Person</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
        <input type="text" id="contact" class="form-control input-xs" maxlength="100" value="<?php echo $order->ContactPerson; ?>" readonly/>
        <input type="hidden" id="CntctCode" value="<?php echo $order->CntctCode; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Customer Ref. No</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-8">
        <input type="text" id="NumAtCard" class="form-control input-xs" maxlength="100" value="<?php echo $order->NumAtCard; ?>" />
        <input type="hidden" id="phone" value="<?php echo $order->Phone; ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Attn1</label>
      <div class="col-lg-3 col-md-3 col-sm-3-harf col-xs-8">
        <input type="text" id="Attn1" class="form-control input-xs" maxlength="50" value="<?php echo $order->Attn1; ?>" />
      </div>
      <label class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 control-label-xs no-padding-right">Attn2</label>
      <div class="col-lg-3 col-md-3 col-sm-3-harf col-xs-8">
        <input type="text" id="Attn2" class="form-control input-xs" maxlength="50" value="<?php echo $order->Attn2; ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Type</label>
      <div class="col-lg-7 col-md-7-harf col-sm-8-harf col-xs-8">
        <input type="text" id="Type" class="form-control input-xs" maxlength="150" value="<?php echo $order->Type; ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-4 control-label-xs no-padding-right">Project</label>
      <div class="col-lg-7 col-md-7-harf col-sm-8-harf col-xs-8">
        <input type="text" class="form-control input-xs" maxlength="150" id="Project" value="<?php echo $order->Project; ?>" />
      </div>
		</div>
  </div>
</div>
