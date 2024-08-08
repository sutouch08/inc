<!--  Add New Address Modal  --------->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:500px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >Reject Reason</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <textarea id="reject-reason" maxlength="254" rows="3" class="form-control" placeholder="กรุณาระบุเหตุผลในการ Reject" ></textarea>
            <div class="help-block col-xs-12 col-sm-reset inline red" id="reject-error"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-primary btn-100" onclick="reject()">OK</button>
        <button type="button" class="btn btn-xs btn-default btn-100" onclick="closeModal('rejectModal')">Cancel</button>
      </div>
    </div>
  </div>
</div>
