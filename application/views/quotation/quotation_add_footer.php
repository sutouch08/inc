<div class="row">
  <!--- left column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">

			<div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Sales Employee</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <select class="width-100" id="sale_id">
						<option value=""></option>
            <?php echo select_saleman($this->_user->sale_id); ?>
					</select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Remark</label>
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
          <textarea id="comments" maxlength="254" class="form-control" style="height:100px;"></textarea>
        </div>
      </div>

    </div>
  </div>

  <!--- right column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">
      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total Before Discount</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="sysTotalAmount" value="0.00" />
					<input type="hidden" id="totalAmount" value="0.00" />
          <input type="text" class="form-control input-sm text-right" id="totalAmountLabel" value="0.00" disabled>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-6 col-md-4 col-sm-4 col-xs-3 control-label no-padding-right">Discount</label>
        <div class="col-lg-2 col-md-4 col-sm-3 col-xs-3 padding-5">
          <span class="input-icon input-icon-right">
          <input type="number" id="discPrcnt" class="form-control input-sm" value="0.00"/>
          <i class="ace-icon fa fa-percent"></i>
          </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
					<input type="hidden" id="discAmount" value="0.00" />
          <input type="text" id="discAmountLabel" class="form-control input-sm text-right" value="0.00" disabled>
        </div>
      </div>

      <div class="form-group hide">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Rouding</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="number" id="roundDif" class="form-control input-sm text-right" value="0.00" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Tax</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
					<input type="hidden" id="tax" value="0.00" />
          <input type="text" id="taxLabel" class="form-control input-sm text-right" value="0.00" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
					<input type="hidden" id="docTotal" value="0.00"/>
          <input type="text" id="docTotalLabel" class="form-control input-sm text-right" value="0.00" disabled/>
        </div>
      </div>
    </div>
  </div>

  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>
  <div class="divider-hidden"></div>

  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-sm btn-primary btn-100 btn-save" id="btn-save" onclick="saveAdd()">Save</button>
    <button type="button" class="btn btn-sm btn-warning btn-100 btn-save" onclick="leave()">Cancel</button>
    <button type="button" class="btn btn-sm btn-info btn-100 btn-save" id="btn-draft" onclick="saveAsDraft()">Save AS Draft</button>
  </div>
</div>

<script>
	$('#sale_id').select2();
</script>
