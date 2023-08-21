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
          <th class="fix-width-150 middle">Item Code</th>
          <th class="min-width-250 middle">Description.</th>
          <th class="fix-width-100 middle text-right">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
          <th class="fix-width-100 middle text-right">Price</th>
          <th class="fix-width-100 middle text-center">Discount(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-120 middle text-right">Price after discount</th>
          <th class="fix-width-150 middle text-right">Amount before tax</th>
        </tr>
      </thead>
      <tbody id="details-template">
			<?php if(!empty($details)) : ?>
				<?php $no = 1; ?>
				<?php foreach($details as $rs) : ?>
					<?php if($rs->type == 0) : ?>
		        <tr>
							<td class="middle text-center"><?php echo $no; ?></td>
		          <td class="middle"><?php echo $rs->ItemCode; ?></td>
		          <td class="middle"><?php echo $rs->ItemName; ?></td>
		          <td class="middle text-right"><?php echo number($rs->Qty, 2); ?></td>
		          <td class="middle text-center"><?php echo $rs->UomCode; ?></td>
		          <td class="middle text-right"><?php echo number($rs->Price, 2); ?></td>
		          <td class="middle text-center"><?php echo $rs->discLabel; ?></td>
		          <td class="middle text-center"><?php echo $rs->VatGroup; ?></td>
		          <td class="middle text-right"><?php echo number($rs->SellPrice, 2); ?></td>
		          <td class="middle text-right"><?php echo number($rs->LineTotal, 2); ?></td>
		        </tr>
					<?php else : ?>
						<tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle text-center">Text</td>
							<td colspan="10"class="middle" style="white-space:pre-wrap;"><?php echo $rs->LineText; ?></td>
		        </tr>
					<?php endif; ?>
          <?php $no++; ?>
        <?php endforeach; ?>
			<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<hr class="padding-5"/>
