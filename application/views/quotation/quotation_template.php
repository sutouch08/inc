<?php $select_warehouse = select_warehouse_code($whsCode); ?>

<script id="row-template" type="text/x-handlebarsTemplate">
<tr id="row-{{no}}" data-no="{{no}}">
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
  <input type="hidden" id="uid-{{no}}" class="uid" data-no="{{no}}" />
  <input type="hidden" id="tree-type-{{no}}" value="N"/>
  <input type="hidden" id="father-uid-{{no}}" value="" />

  <td class="middle text-center fix-no no" scope="row">{{no}}</td>
	<td class="middle text-center fix-img" scope="row">
    <label id="chk-label-{{no}}">
		  <input type="checkbox" class="ace del-chk" value="{{no}}"/>
		    <span class="lbl"></span>
    </label>
	</td>
	<td class="middle fix-item" scope="row">
		<input type="text" class="form-control input-xs item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-xs item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

  <td class="middle">
    <input type="text" class="form-control input-xs item-detail" data-id="{{no}}" id="itemDetail-{{no}}" />
  </td>

	<td class="middle">
		<input type="number" class="form-control input-xs text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-center" id="uom-{{no}}" disabled/>
	</td>

  <td class="middle hide">
    <input type="text" class="form-control input-xs text-right" id="stdPrice-label-{{no}}" value="" disabled/>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

  <td class="middle hide">
    <input type="text" class="form-control input-xs text-right" id="sys-disc-label-{{no}}"  value="" disabled />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc1" id="disc1-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc2" id="disc2-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc3" id="disc3-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <select id="whs-{{no}}" class="form-control input-xs" onchange="getStock({{no}})">
      <option value="">Please select</option>
      <?php echo $select_warehouse; ?>
    </select>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-center" id="vat-code-{{no}}" value="" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right" id="sell-price-{{no}}" value="" readonly disabled>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right number input-amount" id="total-label-{{no}}" readonly disabled />
	</td>

  <td class="middle hide">
		<input type="text" class="form-control input-xs" id="onhand-{{no}}" disabled/>
	</td>
	<td class="middle hide">
		<input type="text" class="form-control input-xs" id="commited-{{no}}" disabled/>
	</td>

	<td class="middle hide">
		<input type="text" class="form-control input-xs" id="onorder-{{no}}" disabled/>
	</td>
</tr>
</script>


<script id="childRow-template" type="text/x-handlebarsTemplate">
<tr id="row-{{no}}" data-no="{{no}}" class="child">
  <input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
  <input type="hidden" id="stdPrice-{{no}}" value="{{Price}}" />
	<input type="hidden" id="price-{{no}}" value="{{Price}}" />
	<input type="hidden" id="sellPrice-{{no}}" value="{{SellPrice}}" />
  <input type="hidden" id="sysSellPrice-{{no}}" value="{{SellPrice}}" />
	<input type="hidden" id="disc-amount-{{no}}" value="{{discAmount}}"/>
	<input type="hidden" id="line-disc-amount-{{no}}" value="{{totalDiscAmount}}" />
	<input type="hidden" id="totalDiscPercent-{{no}}" value="{{totalDiscPercent}}" />
	<input type="hidden" id="line-total-{{no}}" value="{{LineTotal}}" />
  <input type="hidden" id="line-sys-total-{{no}}" value="{{LineTotal}}" />
	<input type="hidden" id="vat-rate-{{no}}" value="{{VatRate}}" />
	<input type="hidden" id="vat-amount-{{no}}" value="{{VatAmount}}" />
	<input type="hidden" id="vat-total-{{no}}" value="{{TotalVatAmount}}" />
	<input type="hidden" id="uom-code-{{no}}" value="{{UomCode}}" />
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
  <input type="hidden" id="uid-{{no}}" class="uid" data-no="{{no}}" value="{{uid}}"/>
  <input type="hidden" id="tree-type-{{no}}" value="{{TreeType}}" value="N"/>
  <input type="hidden" class="child-{{father_uid}}" id="child-{{no}}" data-no="{{no}}" />
  <input type="hidden" id="father-uid-{{no}}" value="{{father_uid}}" />

  <td class="middle text-center fix-no no" scope="row">{{no}}</td>
	<td class="middle text-center fix-img" scope="row">
    <label id="chk-label-{{no}}" class="hide">
      <input type="checkbox" class="ace del-chk" value="{{no}}"/>
        <span class="lbl"></span>
    </label>
	</td>
	<td class="middle fix-item" scope="row">
		<input type="text" class="form-control input-xs item-code" data-id="{{no}}" id="itemCode-{{no}}" value="{{ItemCode}}" disabled/>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-xs item-name" data-id="{{no}}" id="itemName-{{no}}" value="{{ItemName}}" disabled/>
	</td>

  <td class="middle">
    <input type="text" class="form-control input-xs item-detail" data-id="{{no}}" id="itemDetail-{{no}}" value="{{Description}}" disabled/>
  </td>

	<td class="middle">
		<input type="number" class="form-control input-xs text-right line-qty {{father_uid}}" data-id="{{no}}" id="line-qty-{{no}}" value="{{Qty}}" disabled/>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-xs text-center" id="uom-{{no}}" value="{{UomName}}" disabled/>
	</td>

  <td class="middle hide">
    <input type="text" class="form-control input-xs text-right" id="stdPrice-label-{{no}}" value="{{stdPriceLabel}}" disabled/>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right number price" data-id="{{no}}" id="price-label-{{no}}" value="{{priceLabel}}"/>
	</td>

  <td class="middle hide">
    <input type="text" class="form-control input-xs text-right" id="sys-disc-label-{{no}}"  value="{{sysDiscLabel}}" disabled />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc1" id="disc1-{{no}}" value="{{disc1}}" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc2" id="disc2-{{no}}" value="{{disc2}}" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <input type="number" class="form-control input-xs text-right disc3" id="disc3-{{no}}" value="{{disc3}}" onchange="recalDiscount({{no}})" />
  </td>

  <td class="middle">
    <select id="whs-{{no}}" class="form-control input-xs" onchange="getStock({{no}})">
      <option value="">Please select</option>
      <?php echo $select_warehouse; ?>
    </select>
  </td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-center" id="vat-code-{{no}}" value="{{VatGroup}}" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right" id="sell-price-{{no}}" value="{{sellPriceLabel}}" readonly disabled>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-xs text-right number input-amount" id="total-label-{{no}}" value="{{lineTotalLabel}}" readonly disabled />
	</td>

  <td class="middle hide">
		<input type="text" class="form-control input-xs" id="onhand-{{no}}" value="{{OnHand}}" disabled/>
	</td>
	<td class="middle hide">
		<input type="text" class="form-control input-xs" id="commited-{{no}}" value="{{Commited}}" disabled/>
	</td>

	<td class="middle hide">
		<input type="text" class="form-control input-xs" id="onorder-{{no}}" value="{{OnOrder}}" disabled/>
	</td>

</tr>
</script>