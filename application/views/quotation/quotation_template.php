<?php $select_warehouse = select_warehouse_code($whsCode); ?>
<?php $select_uom = select_uom(); ?>

<script id="row-template" type="text/x-handlebarsTemplate">
<tr id="row-{{no}}" data-no="{{no}}" class="rows">
  <input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
  <input type="hidden" id="stdPrice-{{no}}" value="0" />
	<input type="hidden" id="price-{{no}}" value="0" />
  <input type="hidden" id="cost-{{no}}" value="0" />
  <input type="hidden" id="bCost-{{no}}" value="0" />
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
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" data-no="{{no}}" value="0" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
  <input type="hidden" id="uid-{{no}}" class="uid" data-no="{{no}}" />
  <input type="hidden" id="tree-type-{{no}}" value="N"/>
  <input type="hidden" id="father-uid-{{no}}" value="" />
  <input type="hidden" id="is-blank-{{no}}" value="1" data-no="{{no}}"/>

  <td class=" text-center fix-no no" scope="row">{{no}}</td>
  <td class=" text-center fix-add" scope="row">
    <a  class="pointer" href="javascript:insertBefore({{no}})"><i class="fa fa-plus"></i></a>
  </td>
	<td class=" text-center fix-img" scope="row">
    <a class="pointer" href="javascript:removeRow({{no}})" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
    <label class="hide" id="chk-label-{{no}}">
		  <input type="checkbox" class="ace del-chk" value="{{no}}"/>
		    <span class="lbl"></span>
    </label>
	</td>
  <td class=" text-center fix-text" scope="row">
    <a class="pointer hide" id="add-text-{{no}}" href="javascript:insertTextRow({{no}})" title="Insert text row"><i class="fa fa-plus-square-o fa-lg"></i></a>
  </td>
	<td class=" fix-item" scope="row">
		<input type="text" class="form-control input-xs item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="">
		<input type="text" class="form-control input-xs item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

  <td class="">
    <input type="text" class="form-control input-xs item-detail" data-id="{{no}}" id="itemDetail-{{no}}" />
  </td>

	<td class="">
		<input type="number" class="form-control input-xs text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>

	<td class="">
    <select class="form-control input-xs" id="uom-{{no}}" disabled>
      <option value=""></option>
      <?php echo $select_uom; ?>
    </select>
	</td>

  <td class=" hide">
    <input type="text" class="form-control input-xs text-right" id="stdPrice-label-{{no}}" value="" disabled/>
  </td>

	<td class="">
		<input type="text" class="form-control input-xs text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

  <td class=" hide">
    <input type="text" class="form-control input-xs text-right" id="sys-disc-label-{{no}}"  value="" disabled />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc1" id="disc1-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc2" id="disc2-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc3" id="disc3-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <select id="whs-{{no}}" class="form-control input-xs" onchange="getStock({{no}})">
      <option value="">Please select</option>
      <?php echo $select_warehouse; ?>
    </select>
  </td>

	<td class="">
		<input type="text" class="form-control input-xs text-center" id="vat-code-{{no}}" value="" disabled/>
	</td>

	<td class="">
		<input type="text" class="form-control input-xs text-right" id="sell-price-{{no}}" value="" readonly disabled>
	</td>

	<td class="">
		<input type="text" class="form-control input-xs text-right number input-amount" id="total-label-{{no}}" readonly disabled />
	</td>

  <td class="">
		<input type="text" class="form-control input-xs" id="onhand-{{no}}" disabled/>
	</td>
	<td class=" hide">
		<input type="text" class="form-control input-xs" id="commited-{{no}}" disabled/>
	</td>

	<td class=" hide">
		<input type="text" class="form-control input-xs" id="onorder-{{no}}" disabled/>
	</td>
</tr>
</script>


<script id="childRow-template" type="text/x-handlebarsTemplate">
  <tr id="row-{{no}}" data-no="{{no}}" class="rows child">
    <input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
    <input type="hidden" id="stdPrice-{{no}}" value="{{Price}}" />
    <input type="hidden" id="cost-{{no}}" value="{{Cost}}" />
  	<input type="hidden" id="price-{{no}}" value="{{Price}}" />
    <input type="hidden" id="bCost-{{no}}" value="0" />
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
  	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" data-no="{{no}}" value="0" />
  	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
    <input type="hidden" id="uid-{{no}}" class="uid" data-no="{{no}}" value="{{uid}}"/>
    <input type="hidden" id="tree-type-{{no}}" value="{{TreeType}}" value="N"/>
    <input type="hidden" class="child-{{father_uid}}" id="child-{{no}}" data-no="{{no}}" />
    <input type="hidden" id="father-uid-{{no}}" value="{{father_uid}}" />
    <input type="hidden" id="is-blank-{{no}}" value="0" data-no="{{no}}"/>

    <td class=" text-center fix-no no" scope="row">{{no}}</td>
    <td class=" text-center fix-add" scope="row"></td>
  	<td class=" text-center fix-img" scope="row"></td>
    <td class=" text-center fix-text" scope="row">
      <a class="pointer" id="add-text-{{no}}" href="javascript:insertTextRow({{no}})" title="Insert text row"><i class="fa fa-plus-square-o fa-lg"></i></a>
    </td>
  	<td class=" fix-item" scope="row">
  		<input type="text" class="form-control input-xs item-code" data-id="{{no}}" id="itemCode-{{no}}" value="{{ItemCode}}" disabled/>
  	</td>
  	<td class="">
  		<input type="text" class="form-control input-xs item-name" data-id="{{no}}" id="itemName-{{no}}" value="{{ItemName}}" disabled/>
  	</td>

    <td class="">
      <input type="text" class="form-control input-xs item-detail" data-id="{{no}}" id="itemDetail-{{no}}" value="{{Description}}" disabled/>
    </td>

  	<td class="">
  		<input type="number" class="form-control input-xs text-right line-qty {{father_uid}}" data-id="{{no}}" data-qty="{{Qty}}" id="line-qty-{{no}}" value="{{Qty}}" disabled/>
  	</td>
  	<td class="">
      <select class="form-control input-xs" id="uom-{{no}}" disabled>
        <option value=""></option>
        <?php echo $select_uom; ?>
      </select>
  	</td>

    <td class=" hide">
      <input type="text" class="form-control input-xs text-right" id="stdPrice-label-{{no}}" value="{{stdPriceLabel}}" disabled/>
    </td>

  	<td class="">
  		<input type="text" class="form-control input-xs text-right number price" data-id="{{no}}" id="price-label-{{no}}" value="{{priceLabel}}"/>
  	</td>

    <td class=" hide">
      <input type="text" class="form-control input-xs text-right" id="sys-disc-label-{{no}}"  value="{{sysDiscLabel}}" disabled />
    </td>

    <td class="">
      <input type="number" class="form-control input-xs text-right disc1" id="disc1-{{no}}" value="{{disc1}}" onchange="recalDiscount({{no}})" />
    </td>

    <td class="">
      <input type="number" class="form-control input-xs text-right disc2" id="disc2-{{no}}" value="{{disc2}}" onchange="recalDiscount({{no}})" />
    </td>

    <td class="">
      <input type="number" class="form-control input-xs text-right disc3" id="disc3-{{no}}" value="{{disc3}}" onchange="recalDiscount({{no}})" />
    </td>

    <td class="">
      <select id="whs-{{no}}" class="form-control input-xs" onchange="getStock({{no}})">
        <option value="">Please select</option>
        <?php echo $select_warehouse; ?>
      </select>
    </td>

  	<td class="">
  		<input type="text" class="form-control input-xs text-center" id="vat-code-{{no}}" value="{{VatGroup}}" disabled/>
  	</td>

  	<td class="">
  		<input type="text" class="form-control input-xs text-right" id="sell-price-{{no}}" value="{{sellPriceLabel}}" readonly disabled>
  	</td>

  	<td class="">
  		<input type="text" class="form-control input-xs text-right number input-amount" id="total-label-{{no}}" value="{{lineTotalLabel}}" readonly disabled />
  	</td>

    <td class="">
  		<input type="text" class="form-control input-xs text-right" id="onhand-{{no}}" value="{{OnHand}}" disabled/>
  	</td>
  	<td class=" hide">
  		<input type="text" class="form-control input-xs text-right" id="commited-{{no}}" value="{{Commited}}" disabled/>
  	</td>

  	<td class=" hide">
  		<input type="text" class="form-control input-xs text-right" id="onorder-{{no}}" value="{{OnOrder}}" disabled/>
  	</td>

  </tr>
</script>

<script id="text-template" type="text/x-handlebarsTemplate">
  <tr id="row-{{no}}" data-no="{{no}}" class="rows">
    <td class=" text-center fix-no no" scope="row">{{no}}</td>
    <td class=" text-center fix-add" scope="row"></td>
    <td class=" text-center fix-img" scope="row">
      <a class="pointer" href="javascript:removeTextRow({{no}}, {{parentNo}})" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
    </td>
    <td class=" text-center fix-text" scope="row"></td>
    <td class=" text-center fix-item" scope="row"></td>
    <td class="" colspan="2">
      <textarea id="text-{{uid}}" data-no="{{no}}" data-parent="{{parentNo}}" class="autosize autosize-transition form-control" style="min-height:30px; border:0px;"></textarea>
    </td>
    <td colspan="10"></td>
  </tr>
</script>

<script id="normal-template" type="text/x-handlebarsTemplate">
  <input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
  <input type="hidden" id="stdPrice-{{no}}" value="0" />
	<input type="hidden" id="price-{{no}}" value="0" />
  <input type="hidden" id="cost-{{no}}" value="0" />
  <input type="hidden" id="bCost-{{no}}" value="0" />
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
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" data-no="{{no}}" value="0" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
  <input type="hidden" id="uid-{{no}}" class="uid" data-no="{{no}}" />
  <input type="hidden" id="tree-type-{{no}}" value="N"/>
  <input type="hidden" id="father-uid-{{no}}" value="" />
  <input type="hidden" id="is-blank-{{no}}" value="1" data-no="{{no}}"/>

  <td class=" text-center fix-no no" scope="row">{{no}}</td>
  <td class=" text-center fix-add" scope="row">
    <a  class="pointer" href="javascript:insertBefore({{no}})"><i class="fa fa-plus"></i></a>
  </td>
	<td class=" text-center fix-img" scope="row">
    <a class="pointer" href="javascript:removeRow({{no}})" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
	</td>
  <td class=" text-center fix-text" scope="row">
    <a class="pointer hide" id="add-text-{{no}}" href="javascript:insertTextRow({{no}})" title="Insert text row"><i class="fa fa-plus-square-o fa-lg"></i></a>
  </td>
	<td class=" fix-item" scope="row">
		<input type="text" class="form-control input-xs item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="">
		<input type="text" class="form-control input-xs item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

  <td class="">
    <input type="text" class="form-control input-xs item-detail" data-id="{{no}}" id="itemDetail-{{no}}" />
  </td>

	<td class="">
		<input type="number" class="form-control input-xs text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>

	<td class="">
    <select class="form-control input-xs" id="uom-{{no}}" disabled>
      <option value=""></option>
      <?php echo $select_uom; ?>
    </select>
	</td>

  <td class=" hide">
    <input type="text" class="form-control input-xs text-right" id="stdPrice-label-{{no}}" value="" disabled/>
  </td>

	<td class="">
		<input type="text" class="form-control input-xs text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

  <td class=" hide">
    <input type="text" class="form-control input-xs text-right" id="sys-disc-label-{{no}}"  value="" disabled />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc1" id="disc1-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc2" id="disc2-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <input type="number" class="form-control input-xs text-right disc3" id="disc3-{{no}}" value="" onchange="recalDiscount({{no}})" />
  </td>

  <td class="">
    <select id="whs-{{no}}" class="form-control input-xs" onchange="getStock({{no}})">
      <option value="">Please select</option>
      <?php echo $select_warehouse; ?>
    </select>
  </td>

	<td class="">
		<input type="text" class="form-control input-xs text-center" id="vat-code-{{no}}" value="" disabled/>
	</td>

	<td class="">
		<input type="text" class="form-control input-xs text-right" id="sell-price-{{no}}" value="" readonly disabled>
	</td>

	<td class="">
		<input type="text" class="form-control input-xs text-right number input-amount" id="total-label-{{no}}" readonly disabled />
	</td>

  <td class="">
		<input type="text" class="form-control input-xs" id="onhand-{{no}}" disabled/>
	</td>
	<td class=" hide">
		<input type="text" class="form-control input-xs" id="commited-{{no}}" disabled/>
	</td>

	<td class=" hide">
		<input type="text" class="form-control input-xs" id="onorder-{{no}}" disabled/>
	</td>
</script>
