<!--  Add New Address Modal  --------->
<div class="modal fade" id="shipToModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >Shipping Address</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-horizontal">
              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Street/PO Box</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-Street" maxlength="100"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Street No.</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-StreetNo" maxlength="100" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Block</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-Block" maxlength="100"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">City</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-City" maxlength="100"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Zip Code</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-ZipCode" maxlength="20" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">County</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-County" maxlength="100" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Country</label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  <input type="text" class="form-control input-sm" id="s-Country" maxlength="3" onkeydown="return /[A-Z]/i.test(event.key)" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-success btn-100" onClick="updateShipTo()" >OK</button>
        <button type="button" class="btn btn-xs btn-default btn-100" onclick="closeModal('shipToModal')">Cancel</button>
      </div>
    </div>
  </div>
</div>
