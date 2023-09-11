<div class="tab-pane fade active in" id="content">
  <div class="row" style="margin-left:0; margin-right:0;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height:600px; overflow:scroll; padding:0px; border-top:solid 1px #dddddd;">
      <table class="table table-bordered tableFixHead" style="min-width:1680px;">
        <thead>
          <tr class="font-size-10 freez">
            <th class="fix-width-45 middle text-center fix-no fix-header"></th>
            <th class="fix-width-150 middle text-center fix-item fix-header">Item Code</th>
            <th class="fix-width-200 middle text-center fix-desc fix-header">Item Description.</th>
            <th class="min-width-250 middle text-center">Item Details.</th>
            <th class="fix-width-80 middle text-center">Quantity</th>
            <th class="fix-width-80 middle text-center">Uom</th>
            <th class="fix-width-100 middle text-center hide">Std Price</th>
            <th class="fix-width-100 middle text-center">Price</th>
            <th class="fix-width-100 middle text-center hide">SysDisc(%)</th>
            <th class="fix-width-80 middle text-center">Disc1(%)</th>
            <th class="fix-width-80 middle text-center">Disc2(%)</th>
            <th class="fix-width-80 middle text-center">Disc3(%)</th>
            <th class="fix-width-80 middle text-center">Warehouse</th>
            <th class="fix-width-80 middle text-center">Tax Code</th>
            <th class="fix-width-100 middle text-center">Price after disc</th>
            <th class="fix-width-150 middle text-center">Amount before tax</th>
          </tr>
        </thead>
        <tbody id="details-template">
          <?php $no = 1; ?>
          <?php $rows = 10; ?>
          <?php if(!empty($details)) : ?>
    				<?php foreach($details as $rs) : ?>
              <?php $bg = $rs->TreeType == 'S' ? 'father' : ($rs->TreeType == 'I' ? 'child' : ''); ?>
              <tr id="row-<?php echo $no; ?>" data-no="<?php echo $no; ?>" class="<?php echo $bg; ?>">
                <td class="middle text-center fix-no no" scope="row"><?php echo $no; ?></td>
                <td class="middle fix-item" scope="row"><?php echo $rs->ItemCode; ?></td>
                <td class="middle fix-desc" scope="row"><?php echo $rs->ItemName; ?></td>
                <td class="middle"><?php echo $rs->Description; ?></td>
                <td class="middle text-right"><?php echo number($rs->Qty); ?></td>
                <td class="middle text-center"><?php echo $rs->UomCode; ?></td>
                <td class="middle text-right hide"><?php echo number($rs->stdPrice, 2); ?></td>
                <td class="middle text-right"><?php echo number($rs->Price, 2); ?></td>
                <td class="middle text-right hide"><?php echo $rs->sysDisc; ?></td>
                <td class="middle text-right"><?php echo $rs->disc1; ?></td>
                <td class="middle text-right"><?php echo $rs->disc2; ?></td>
                <td class="middle text-right"><?php echo $rs->disc3; ?></td>
                <td class="middle"><?php echo $rs->WhsCode; ?></td>
                <td class="middle text-center"><?php echo $rs->VatGroup; ?></td>
                <td class="middle text-right"><?php echo number($rs->SellPrice, 2); ?></td>
                <td class="middle text-right"><?php echo number($rs->LineTotal, 2); ?></td>
              </tr>
              <?php $no++; ?>
              <?php $rows--; ?>
            <?php endforeach; ?>
    			<?php endif; ?>

          <?php while($rows > 0) : ?>
            <tr id="row-<?php echo $no; ?>" data-no="<?php echo $no; ?>">
              <td class="middle text-center fix-no no" scope="row"><?php echo $no; ?></td>
              <td class="middle fix-item" scope="row"></td>
              <td class="middle fix-desc" scope="row"></td>
              <td class="middle"></td>
              <td class="middle text-right"></td>
              <td class="middle text-center"></td>
              <td class="middle text-right hide"></td>
              <td class="middle text-right"></td>
              <td class="middle text-right hide"></td>
              <td class="middle text-right"></td>
              <td class="middle text-right"></td>
              <td class="middle text-right"></td>
              <td class="middle"></td>
              <td class="middle text-center"></td>
              <td class="middle text-right"></td>
              <td class="middle text-right"></td>
            </tr>
            <?php $no++; ?>
            <?php $rows--; ?>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
