<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 padding-5">
  <div class="form-horizontal">
    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Customer</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardCode" class="form-control input-xs" value="<?php echo $order->CardCode; ?>" disabled/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Name</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="CardName" class="form-control input-xs" value="<?php echo $order->CardName; ?>" disabled/>
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Contact Person</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="contact" class="form-control input-xs" maxlength="100" value="<?php echo $order->ContactPerson; ?>" disabled/>
        <input type="hidden" id="CntctCode" value="<?php echo $order->CntctCode; ?>" />
      </div>
    </div>

		<div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Customer Ref. No</label>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <input type="text" id="NumAtCard" class="form-control input-xs" maxlength="100" value="<?php echo $order->NumAtCard; ?>" disabled />
        <input type="hidden" id="phone" value="<?php echo $order->Phone; ?>" />
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Attn1</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="Attn1" class="form-control input-xs" maxlength="50" value="<?php echo $order->Attn1; ?>" disabled/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Attn2</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="Attn2" class="form-control input-xs" maxlength="50" value="<?php echo $order->Attn2; ?>" disabled/>
      </div>
    </div>

    <div class="form-group hide">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Type</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="Type" class="form-control input-xs" maxlength="40" value="<?php echo $order->Type; ?>" disabled/>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Project</label>
      <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
        <input type="text" id="Project" class="form-control input-xs" maxlength="100" value="<?php echo $order->Project; ?>" disabled/>
      </div>
    </div>
  </div>
</div>
