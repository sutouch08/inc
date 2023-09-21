<div class="row">
  <!--- left column -->
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <div class="form-horizontal">

			<div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Sales Employee</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control input-sm" value="<?php echo $sale_name; ?>" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 control-label no-padding-right">Owner</label>
        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
          <input type="text" class="form-control input-sm" value="<?php echo $owner; ?>" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-3 col-md-4 col-sm-4 col-xs-12 control-label no-padding-right">Remark</label>
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
          <textarea id="comments" maxlength="254" rows="3" class="form-control" disabled><?php echo $order->Comments; ?></textarea>
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
          <input type="hidden" id="sysTotalAmount" value="<?php echo round($order->SysTotal, 2); ?>" />
					<input type="hidden" id="totalAmount" value="<?php echo round($totalAmount, 2); ?>">
          <input type="text" class="form-control input-sm text-right" id="totalAmountLabel" value="<?php echo number($totalAmount, 2); ?>" disabled>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-6 col-md-4 col-sm-4 col-xs-3 control-label no-padding-right">Discount</label>
        <div class="col-lg-2 col-md-4 col-sm-3 col-xs-3 padding-5">
          <span class="input-icon input-icon-right">
          <input type="number" id="discPrcnt" class="form-control input-sm" value="<?php echo $order->DiscPrcnt; ?>" disabled/>
          <i class="ace-icon fa fa-percent"></i>
          </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="discAmount" value="<?php echo $order->DiscAmount; ?>" />
          <input type="text" id="discAmountLabel" class="form-control input-sm text-right" value="<?php echo number($order->DiscAmount, 2); ?>" disabled>
        </div>
      </div>

      <div class="form-group hide">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Rouding</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="number" id="roundDif" class="form-control input-sm text-right" value="<?php echo $order->RoundDif; ?>" />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Tax</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="tax" value="<?php echo round($order->VatSum); ?>" />
          <input type="text" id="taxLabel" class="form-control input-sm text-right" value="<?php echo number($order->VatSum, 2); ?>" disabled />
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-8 col-md-8 col-sm-7 col-xs-6 control-label no-padding-right">Total</label>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 padding-5 last">
          <input type="hidden" id="docTotal" value="<?php echo round($order->DocTotal, 2); ?>"/>
          <input type="text" id="docTotalLabel" class="form-control input-sm text-right" value="<?php echo number($order->DocTotal, 2); ?>" disabled/>
        </div>
      </div>
    </div>
    <p class="text-right" style="margin-bottom:0px;">*** ส่วนลดสูงสุด  <?php echo $order->disc_diff; ?> % ***</p>
    <div class="divider-hidden"></div>
    <div class="divider-hidden"></div>
    <p class="pull-right" style="margin-bottom:0px;">
      <button type="button" class="btn btn-xs btn-default btn-100" onclick="viewLogs('<?php echo $order->code; ?>')">View Logs</button>
    <?php if($order->Status == 0) : ?>
      <?php if($order->Review == 'P' && $ap->review) : ?>
              <button type="button" class="btn btn-xs btn-primary btn-100" id="btn-review-confirm" onclick="confirmReview()">Confirm</button>
              <button type="button" class="btn btn-xs btn-warning btn-100" id="btn-review-reject" onclick="confirmReject('review')">Reject</button>
      <?php endif; ?>

      <?php if($ap->approve && $order->Approved == 'P' && ($order->Review == 'A' OR $order->Review == 'S')) : ?>
        <?php if($ap->maxDisc >= $order->disc_diff && $ap->maxAmount >= $order->DocTotal) : ?>
          <button type="button" class="btn btn-xs btn-primary btn-100" id="btn-approve" onclick="approve()">Approve</button>
          <button type="button" class="btn btn-xs btn-danger btn-100" id="btn-reject" onclick="confirmReject('approve')">Reject</button>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  </p>
  </div>
</div>

<?php if($order->Status == 0 && ($order->Review == 'R' OR $order->Approved == 'R')) : ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 red">
    Reject Reason : <?php echo $order->message; ?>
  </div>
</div>
<?php endif; ?>
