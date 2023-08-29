<div class="row">
	<?php $statusName = "Unknow"; ?>

	<?php
	if($order->Status == -1)
	{
		$statusName = "Draft";
	}
	elseif($order->Status == 2)
	{
		$statusName = 'Canceled';
	}
	elseif($order->Status == 3)
	{
		$statusName = "Failed";
	}
	else
	{
		if($order->Status == 1)
		{
			$statusName = "Success";
		}
		else
		{
			if($order->Status == 0 && $order->Approved == 'P')
			{
				$statusName = "Pending";
			}

			if($order->Status == 0 && $order->Approved == 'R')
			{
				$statusName = "Rejected";
			}

			if($order->Status == 0 && $order->Approved == 'A')
			{
				$statusName = "Approved";
			}

			if($order->Status == 0 && $order->Approved == 'S')
			{
				$statusName = "Approve by system";
			}
		}
	}

	?>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-bordered border-1">
			<tr>
				<td class="width-15  bg-grey">Web Order</td>
				<td class="width-55 "><?php echo $order->code; ?></td>
				<td class="width-15 bg-grey ">Posting Date</td>
				<td class="width-15"><?php echo thai_date($order->DocDate, FALSE); ?></td>
			</tr>

			<tr>
				<td class="bg-grey">Customer Code</td>
				<td class=""><?php echo $order->CardCode; ?></td>
				<td class="bg-grey ">Valid Until</td>
				<td class=""><?php echo thai_date($order->DocDueDate, FALSE); ?></td>
			</tr>
			<tr>
				<td class=" bg-grey">Customer Name</td>
				<td class=""><?php echo $order->CardName; ?></td>
				<td class="bg-grey ">Document Date</td>
				<td class=""><?php echo thai_date($order->TextDate, FALSE); ?></td>
			</tr>
			<tr>
				<td class="bg-grey ">Contact Person</td>
				<td class=""><?php echo $order->ContactPerson; ?></td>
				<td class=" bg-grey">Payment</td>
				<td class=""><?php echo $order->payment_name; ?></td>
			</tr>
			<tr>

				<td class="bg-grey ">Customer Reference</td>
				<td class=""><?php echo $order->NumAtCard; ?></td>

				<td class="bg-grey ">Status</td>
				<td class="">
					<?php if($order->Status == 3) : ?>
						<a haref="#" class="pointer red" onclick="showMessage('<?php echo $order->code; ?>')" class="red">Failed</a>						
					<?php else : ?>
					<?php echo $statusName; ?>
					<?php endif; ?>
				</td>

			</tr>
			<tr>
				<td class=" bg-grey">Ship To (<?php echo $order->ShipToCode;?>)</td>
				<td class=""><?php echo $order->Address2; ?></td>
				<td class="bg-grey ">Owner</td>
				<td class=""><?php echo $owner; ?></td>
				<!--<td class="bg-grey ">Original SQ No.</td>
				<td class=""><?php echo $order->OriginalSQ; ?></td>-->
			</tr>
			<tr>
				<td class="bg-grey ">Bill To (<?php echo $order->PayToCode; ?>)</td>
				<td class=""><?php echo $order->Address; ?></td>
				<td class="bg-grey ">SAP Doc No.</td>
				<td class=""><?php echo $order->DocNum; ?></td>
			</tr>

		</table>
	</div>
</div>
<hr class="padding-5" />
