<style>
  .table > tr > td {
    padding:3px;
  }
</style>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <button type="button" class="btn btn-sm btn-info" onclick="addRow()">Add Row</button>
    <button type="button" class="btn btn-sm btn-warning" onclick="removeRow()">Delete Row</button>
  </div>
  <div class="divider-hidden">

  </div>
  <div class="col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-bordered border-1" style="min-width:1850px;">
      <thead>
        <tr class="font-size-10">
          <th class="fix-width-40 middle text-center"></th>
          <th class="fix-width-150 middle text-center">Item Code</th>
          <th class="min-width-250 middle text-center">Description.</th>
          <th class="fix-width-150 middle text-center">Warehouse</th>
					<th class="fix-width-80 middle text-center">OnHand</th>
          <th class="fix-width-80 middle text-center">Commited</th>
          <th class="fix-width-80 middle text-center">OnOrder</th>
          <th class="fix-width-100 middle text-center">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
          <th class="fix-width-100 middle text-center">Std Price</th>
          <th class="fix-width-100 middle text-center">Price</th>
          <th class="fix-width-100 middle text-center">SysDisc(%)</th>
          <th class="fix-width-80 middle text-center">Disc1(%)</th>
          <th class="fix-width-80 middle text-center">Disc2(%)</th>
          <th class="fix-width-80 middle text-center">Disc3(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-100 middle text-center">Price after discount</th>
          <th class="fix-width-150 middle text-center">Amount before tax</th>
        </tr>
      </thead>
      <tbody id="details-template">
        <?php $rows = 5; ?>
        <?php $no = 1; ?>
			<?php if(!empty($details)) : ?>

				<?php foreach($details as $rs) : ?>
          <tr id="row-<?php echo $no; ?>">
            <input type="hidden" class="line-num" id="line-num-<?php echo $no; ?>" value="<?php echo $no; ?>" />
            <input type="hidden" id="stdPrice-<?php echo $no; ?>" value="<?php echo $rs->stdPrice; ?>" />
            <input type="hidden" id="price-<?php echo $no; ?>" value="<?php echo $rs->Price; ?>" />
            <input type="hidden" id="sellPrice-<?php echo $no; ?>" value="<?php echo $rs->SellPrice; ?>" />
            <input type="hidden" id="sysSellPrice-<?php echo $no; ?>" value="<?php echo $rs->sysSellPrice; ?>" />
            <input type="hidden" id="disc-amount-<?php echo $no; ?>" value="<?php echo $rs->discAmount; ?>"/>
            <input type="hidden" id="line-disc-amount-<?php echo $no; ?>" value="<?php echo $rs->totalDiscAmount; ?>" />
            <input type="hidden" id="totalDiscPercent-<?php echo $no; ?>" value="<?php echo $rs->DiscPrcnt; ?>" />
            <input type="hidden" id="line-total-<?php echo $no; ?>" value="<?php echo $rs->LineTotal; ?>" />
            <input type="hidden" id="line-sys-total-<?php echo $no; ?>" value="<?php echo $rs->LineSysTotal; ?>" />
            <input type="hidden" id="vat-rate-<?php echo $no; ?>" value="<?php echo $rs->VatRate; ?>" />
            <input type="hidden" id="vat-amount-<?php echo $no; ?>" value="<?php echo $rs->VatAmount; ?>" />
            <input type="hidden" id="vat-total-<?php echo $no; ?>" value="<?php echo $rs->totalVatAmount; ?>" />
            <input type="hidden" id="uom-code-<?php echo $no; ?>" value="<?php echo $rs->UomCode; ?>" />
            <input type="hidden" class="disc-diff" id="disc-diff-<?php echo $no; ?>" value="<?php echo $rs->discDiff; ?>" />
            <input type="hidden" class="disc-error" id="disc-error-<?php echo $no; ?>" value="0" data-id="<?php echo $no; ?>" />

            <td class="middle text-center">
              <input type="checkbox" class="ace del-chk" value="<?php echo $no; ?>"/>
              <span class="lbl"></span>
            </td>
            <td class="middle">
              <input type="text" class="form-control input-sm item-code" data-id="<?php echo $no; ?>" id="itemCode-<?php echo $no; ?>" value="<?php echo $rs->ItemCode; ?>"  />
            </td>
            <td class="middle">
              <input type="text" class="form-control input-sm item-name" data-id="<?php echo $no; ?>" id="itemName-<?php echo $no; ?>" value="<?php echo $rs->ItemName; ?>"  />
            </td>

            <td class="middle">
              <select id="whs-<?php echo $no; ?>" onchange="getStock(<?php echo $no; ?>)">
                <option value="">Please select</option>
                <?php echo select_warehouse_code($rs->WhsCode); ?>
              </select>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm" id="onhand-<?php echo $no; ?>" value="<?php echo number($rs->OnHand); ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm" id="commited-<?php echo $no; ?>" value="<?php echo number($rs->Commited); ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm" id="onorder-<?php echo $no; ?>" value="<?php echo number($rs->OnOrder); ?>" disabled/>
            </td>

            <td class="middle">
              <input type="number" class="form-control input-sm text-right line-qty"
              data-id="<?php echo $no; ?>" id="line-qty-<?php echo $no; ?>"
              value="<?php echo $rs->Qty; ?>" onkeyup="recalAmount(<?php echo $no; ?>)" />
            </td>
            <td class="middle">
              <input type="text" class="form-control input-sm text-center" id="uom-<?php echo $no; ?>" value="<?php echo $rs->UomCode; ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right" id="stdPrice-label-<?php echo $no; ?>" value="<?php echo number($rs->stdPrice, 2); ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right number price"
              data-id="<?php echo $no; ?>" id="price-label-<?php echo $no; ?>"
              value="<?php echo number($rs->Price, 2); ?>" />
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right " id="sys-disc-label-<?php echo $no; ?>"
              value="<?php echo $rs->sysDisc; ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right disc disc-<?php echo $no; ?>" id="disc1-<?php echo $no; ?>"
              value="<?php echo $rs->disc1; ?>" onchange="recalDiscount(<?php echo $no; ?>)" />
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right disc disc-<?php echo $no; ?>" id="disc2-<?php echo $no; ?>"
              value="<?php echo $rs->disc2; ?>" onchange="recalDiscount(<?php echo $no; ?>)" />
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right disc disc-<?php echo $no; ?>" id="disc3-<?php echo $no; ?>"
              value="<?php echo $rs->disc3; ?>" onchange="recalDiscount(<?php echo $no; ?>)" />
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-center" id="vat-code-<?php echo $no; ?>" value="<?php echo $rs->VatGroup; ?>" disabled/>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right" id="sell-price-<?php echo $no; ?>" value="<?php echo number($rs->SellPrice, 2); ?>" readonly disabled>
            </td>

            <td class="middle">
              <input type="text" class="form-control input-sm text-right number input-amount" id="total-label-<?php echo $no; ?>" value="<?php echo number($rs->LineTotal, 2); ?>" readonly disabled />
            </td>
          </tr>
          <?php $no++; ?>
        <?php endforeach; ?>
			<?php endif; ?>
      </tbody>
    </table>
		<input type="hidden" id="row-no" value="<?php echo $no; ?>" />
  </div>
</div>
<hr class="padding-5"/>
<script id="row-template" type="text/x-handlebarsTemplate">
<tr id="row-{{no}}">
  <input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
  <input type="hidden" id="stdPrice-{{no}}" value="0" />
	<input type="hidden" id="price-{{no}}" value="0" />
	<input type="hidden" id="sellPrice-{{no}}" value="0" />
  <input type="hidden" id="sysSellPrice-{{no}}" value="0" />
	<input type="hidden" id="disc-amount-{{no}}" value="0"/>
	<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
	<input type="hidden" id="totalDiscPercent-{{no}}" value="0.00" />
	<input type="hidden" id="line-total-{{no}}" value="0" />
  <input type="hidden" id="line-sys-total-{{no}}" value="0" />
	<input type="hidden" id="vat-rate-{{no}}" value="0" />
	<input type="hidden" id="vat-amount-{{no}}" value="0" />
	<input type="hidden" id="vat-total-{{no}}" value="0" />
	<input type="hidden" id="uom-code-{{no}}" value="" />
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
	<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>

	<td class="middle text-center">
		<input type="checkbox" class="ace del-chk" value="{{no}}"/>
		<span class="lbl"></span>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

  <td class="middle">
    <select id="whs-{{no}}" onchange="getStock({{no}})">
      <option value="">Please select</option>
      <?php echo select_warehouse_code($whsCode); ?>
    </select>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="onhand-{{no}}" disabled/>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm" id="commited-{{no}}" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="onorder-{{no}}" disabled/>
	</td>

	<td class="middle">
		<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
	</td>

  <td class="middle">
    <input type="text" class="form-control input-sm text-right" id="stdPrice-label-{{no}}" value="" disabled/>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

  <td class="middle">
    <input type="text" class="form-control input-sm text-right" id="sys-disc-label-{{no}}"  value="" disabled />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-sm text-right disc1" id="disc1-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-sm text-right disc2" id="disc2-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-sm text-right disc3" id="disc3-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-center" id="vat-code-{{no}}" value="" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right" id="sell-price-{{no}}" value="" readonly disabled>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right number input-amount" id="total-label-{{no}}" readonly disabled />
	</td>
</tr>
</script>
