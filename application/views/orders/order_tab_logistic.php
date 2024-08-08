<div class="tab-pane fade" id="logistic" style="height:341px;">
  <div class="row" style="margin-left:0; margin-right:0;">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Bill To</label>
          <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
            <select class="form-control input-xs" id="billToCode" onchange="get_address_bill_to()">
              <?php echo select_bill_to_code($order->CardCode, $order->PayToCode); ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right"></label>
          <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
            <textarea id="BillTo" class="form-control input-xs" rows="5" readonly><?php echo $order->Address; ?></textarea>
            <span class="badge badge-yellow pull-right margin-top-5"
            style="padding-bottom:0px; padding-top:0px; border-radius:3px; cursor:pointer;" onclick="editBillTo()">
              <i class="fa fa-ellipsis-h"></i>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="form-horizontal">
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right">Ship To</label>
          <div class="col-lg-3-harf col-md-4 col-sm-4-harf col-xs-12">
            <select class="form-control input-xs" id="shipToCode" onchange="get_address_ship_to()">
              <?php echo select_ship_to_code($order->CardCode, $order->ShipToCode); ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-12 control-label-xs no-padding-right"></label>
          <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
            <textarea id="ShipTo" class="form-control input-xs" rows="5" readonly><?php echo $order->Address2; ?></textarea>
            <span class="badge badge-yellow pull-right margin-top-5"
            style="padding-bottom:0px; padding-top:0px; border-radius:3px; cursor:pointer;" onclick="editShipTo()">
              <i class="fa fa-ellipsis-h"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" id="Street-s" value="<?php echo empty($Address) ? NULL : $Address->sStreet; ?>"/>
  <input type="hidden" id="StreetNo-s" value="<?php echo empty($Address) ? NULL : $Address->sStreetNo; ?>"/>
  <input type="hidden" id="Block-s" value="<?php echo empty($Address) ? NULL : $Address->sBlock; ?>"/>
  <input type="hidden" id="City-s" value="<?php echo empty($Address) ? NULL : $Address->sCity; ?>"/>
  <input type="hidden" id="ZipCode-s" value="<?php echo empty($Address) ? NULL : $Address->sZipCode; ?>"/>
  <input type="hidden" id="County-s" value="<?php echo empty($Address) ? NULL : $Address->sCounty; ?>"/>
  <input type="hidden" id="Country-s" value="<?php echo empty($Address) ? NULL : $Address->sCountry; ?>"/>

  <input type="hidden" id="Street-b" value="<?php echo empty($Address) ? NULL : $Address->bStreet; ?>"/>
  <input type="hidden" id="StreetNo-b" value="<?php echo empty($Address) ? NULL : $Address->bStreetNo; ?>"/>
  <input type="hidden" id="Block-b" value="<?php echo empty($Address) ? NULL : $Address->bBlock; ?>"/>
  <input type="hidden" id="City-b" value="<?php echo empty($Address) ? NULL : $Address->bCity; ?>"/>
  <input type="hidden" id="ZipCode-b" value="<?php echo empty($Address) ? NULL : $Address->bZipCode; ?>"/>
  <input type="hidden" id="County-b" value="<?php echo empty($Address) ? NULL : $Address->bCounty; ?>"/>
  <input type="hidden" id="Country-b" value="<?php echo empty($Address) ? NULL : $Address->bCountry; ?>"/>
</div>
