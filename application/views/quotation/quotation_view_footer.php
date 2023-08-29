<div class="row">

	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 padding-5">
		<table class="table">
			<tr>
				<td class="no-border">Sales Employee : <?php echo $sale_name; ?></td>
			</tr>
			<!--<tr>
				<td class="no-border">Owner : <?php echo $owner; ?></td>
			</tr> -->
			<tr>
				<td class="no-border">Remark : <?php echo $order->Comments; ?></td>
			</tr>
		</table>
  </div>


  <!--- right column -->
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-5 text-right">
		<table class="table table-striped table-bordered">
			<tr>
				<td class="width-60 xxx text-right">Total Before Discount</td>
				<td class="width-40 xxx text-right"><?php echo number(round($totalAmount, 2), 2); ?></td>
			</tr>
			<tr>
				<td class="width-60 xxx text-right">Discount &nbsp; <?php echo $order->DiscPrcnt; ?>% </td>
				<td class="width-40 xxx text-right"><?php echo number($order->DiscAmount, 2); ?></td>
			</tr>

			<tr>
				<td class="width-60 xxx text-right">Tax</td>
				<td class="width-40 xxx text-right"><?php echo number($order->VatSum, 2); ?></td>
			</tr>
			<tr>
				<td class="width-60 xxx text-right">Total</td>
				<td class="width-40 xxx text-right"><?php echo number($order->DocTotal, 2); ?></td>
			</tr>
		</table>
		<?php if($order->Status == 0) : ?>
			<?php if($order->Review == 'P' && $ap->review) : ?>
							<button type="button" class="btn btn-sm btn-primary btn-100" id="btn-review-confirm" onclick="confirmReview()">Confirm</button>
							<button type="button" class="btn btn-sm btn-warning btn-100" id="btn-review-reject" onclick="rejectReview()">Reject</button>
			<?php endif; ?>

			<?php if($ap->approve && $order->Approved == 'P' && ($order->Review == 'A' OR $order->Review == 'S')) : ?>
				<?php if($ap->maxDisc >= $order->disc_diff && $ap->maxAmount >= $order->DocTotal) : ?>
					<button type="button" class="btn btn-sm btn-primary btn-100" id="btn-approve" onclick="approve()">Approve</button>
					<button type="button" class="btn btn-sm btn-danger btn-100" id="btn-reject" onclick="reject()">Reject</button>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

  </div>

  <div class="divider-hidden"></div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <?php if(!empty($logs)) : ?>
			<?php foreach($logs as $lg) : ?>
				<p class="p-logs">
					<?php echo action_name($lg->action); ?>  โดย <?php echo $lg->uname; ?> วันที่ <?php echo thai_date($lg->date_upd, TRUE); ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
  </div>

</div>
