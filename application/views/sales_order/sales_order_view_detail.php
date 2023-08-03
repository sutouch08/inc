<style>
  .table > tr > td {
    padding:3px;
  }
</style>

<div class="row">
  <div class="col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1" style="min-width:1300px;">
      <thead>
        <tr class="font-size-10">
					<th class="fix-width-40 text-center">#</th>
          <th class="fix-width-60 middle text-center"></th>
          <th class="fix-width-150 middle">Item Code</th>
          <th class="min-width-250 middle">Description.</th>
          <th class="fix-width-100 middle text-right">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
					<th class="fix-width-100 middle text-right">Std Price</th>
          <th class="fix-width-100 middle text-right">Price</th>
          <th class="fix-width-150 middle text-center">Discount(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-100 middle text-right">Price after discount</th>
          <th class="fix-width-150 middle text-right">Amount before tax</th>
					<th class="fix-width-60 middle text-center">Free</th>
					<th class="fix-width-100 middle text-center">Discount Rule</th>
        </tr>
      </thead>
      <tbody id="details-template">
			<?php if(!empty($details)) : ?>
				<?php $no = 1; ?>
				<?php $not_ap = array(); ?>

				<?php foreach($details as $rs) : ?>
					<?php $hilight = ""; ?>
					<?php
					if($order->must_approve && $is_approver && $rs->discDiff > 0)
					{
						if(isset($brand[$rs->product_brand_id]))
						{
							$max_disc = $brand[$rs->product_brand_id];

							if($rs->discDiff > $max_disc)
							{
								$this->can_approve = FALSE;
								$hilight = "color:red;";

								if(! isset($not_ap[$rs->product_brand_id]))
								{
									$not_ap[$rs->product_brand_id]['name'] = $rs->brand_name;
									$not_ap[$rs->product_brand_id]['disc'] = $rs->discDiff;
								}
								else
								{
									if($not_ap[$rs->product_brand_id]['disc'] < $rs->discDiff)
									{
										$not_ap[$rs->product_brand_id]['disc'] = $rs->discDiff;
									}
								}
							}
						}
					}
					?>

        <tr style="<?php echo $hilight; ?>">
					<td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center" id="img-<?php echo $no; ?>"><img src="<?php echo $rs->image; ?>" width="40" height="40" /></td>
          <td class="middle"><?php echo $rs->ItemCode; ?></td>
          <td class="middle"><?php echo $rs->ItemName; ?></td>
          <td class="middle text-right"><?php echo number($rs->Qty, 2); ?></td>
          <td class="middle text-center"><?php echo $rs->uom_name; ?></td>
					<td class="middle text-right"><?php echo number($rs->StdPrice, 2); ?></td>
          <td class="middle text-right"><?php echo number($rs->Price, 2); ?></td>
          <td class="middle text-center"><?php echo $rs->discLabel; ?></td>
          <td class="middle text-center"><?php echo $rs->VatGroup; ?></td>
          <td class="middle text-right"><?php echo number($rs->SellPrice, 4); ?></td>
          <td class="middle text-right"><?php echo number($rs->LineTotal, 2); ?></td>
					<td class="middle text-center"><?php echo ($rs->is_free == 1 ? 'Free' : ''); ?></td>
					<td class="middle text-center"><?php echo $rs->ruleCode; ?></td>
        </tr>
          <?php $no++; ?>
        <?php endforeach; ?>
			<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $this->not_ap = $not_ap; ?>
<hr class="padding-5"/>
