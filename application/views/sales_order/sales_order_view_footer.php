<div class="row">

	<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 padding-5">
		<table class="table">
			<tr>
				<td class="no-border">Sales Employee : <?php echo $sale_name; ?></td>

			</tr>
			<tr>
				<td class="no-border">Customer Team : <?php echo $order->sale_team_name; ?></td>


			</tr>
			<tr>
				<td class="no-border">Owner : <?php echo $owner; ?></td>

			</tr>
			<tr>
				<td class="no-border">Remark : <?php echo $order->Comments; ?></td>
			</tr>
		</table>
  </div>


  <!--- right column -->
  <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 padding-5">
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

  </div>

  <div class="divider-hidden hidden-xs"></div>
  <div class="divider-hidden hidden-xs"></div>
  <div class="divider-hidden"></div>

	<div class="col-lg-8 col-md-8 col-sm-7 hidden-xs padding-5"></div>

  <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 padding-5 text-right">
		<?php if($order->Status == 0 && ($is_approver OR $this->_SuperAdmin)) : ?>
			<?php if($order->must_approve == 1 && $order->Approved == 'P' && ($this->can_approve OR $this->_SuperAdmin) && ($this->readOnly === FALSE OR $this->_SuperAdmin)) : ?>
				<button type="button" class="btn btn-sm btn-success btn-100" onclick="doApprove('<?php echo $order->code; ?>')">Approve</button>
				<button type="button" class="btn btn-sm btn-danger btn-100" onclick="doReject('<?php echo $order->code; ?>')">Reject</button>
			<?php else : ?>
				<p class="red" >คุณต้องมีสิทธิ์ในการอนุมัติ</p>
				<?php if(! empty($this->not_ap)) : ?>
					<?php foreach($this->not_ap as $nap) : ?>
						<p class="red"><?php echo $nap['name']; ?> : <?php echo $nap['disc'].' %'; ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
  </div>
	<div class="divider-hidden visible-xs"></div>
	<div class="divider-hidden visible-xs"></div>
	<div class="divider-hidden visible-xs"></div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <?php if(!empty($logs)) : ?>
			<?php foreach($logs as $lg) : ?>
				<p style="font-size:12px; font-style:italic; color:#729fe1;">
					<?php echo action_name($lg->action); ?>  โดย <?php echo $lg->uname; ?> วันที่ <?php echo thai_date($lg->date_upd, TRUE); ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
  </div>
</div>
