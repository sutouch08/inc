<div class="tab-pane fade" id="logistic" style="height:300px;">
  <div class="row" style="margin-left:0; margin-right:0;">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Bill To</label>
          <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
            <select class="form-control input-xs" id="billToCode" disabled>
              <?php echo select_bill_to_code($order->CardCode, $order->PayToCode); ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right"></label>
          <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
            <textarea id="BillTo" class="form-control input-xs" rows="5" disabled><?php echo $order->Address; ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Ship To</label>
          <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
            <select class="form-control input-xs" id="shipToCode" disabled>
              <?php echo select_ship_to_code($order->CardCode, $order->ShipToCode); ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right"></label>
          <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
            <textarea id="ShipTo" class="form-control input-xs" rows="5" disabled><?php echo $order->Address2; ?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
