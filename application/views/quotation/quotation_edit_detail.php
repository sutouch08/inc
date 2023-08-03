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
    <table class="table table-bordered border-1" style="min-width:1700px;">
      <thead>
				<tr class="font-size-10">
          <th class="fix-width-40 middle text-center"></th>
					<th class="fix-width-100 middle text-center">Type</th>
          <th class="fix-width-60 middle text-center">Image</th>
          <th class="fix-width-150 middle text-center">Item Code</th>
          <th class="min-width-250 middle text-center">Description.</th>
					<th class="fix-width-100 middle text-center">Warehouse</th>
					<th class="fix-width-80 middle text-center">In Stock</th>
					<th class="fix-width-100 middle text-center">Quota No.</th>
					<th class="fix-width-80 middle text-center">Quota</th>
          <th class="fix-width-80 middle text-center">Commited</th>
          <th class="fix-width-80 middle text-center">Available</th>
          <th class="fix-width-100 middle text-center">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
          <th class="fix-width-150 middle text-center">Price</th>
          <th class="fix-width-150 middle text-center">Discount(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-100 middle text-center">Price after discount</th>
          <th class="fix-width-150 middle text-center">Amount before tax</th>
        </tr>
      </thead>
      <tbody id="details-template">
        <?php $rows = 5; ?>
        <?php $no = 1; ?>
				<?php $whs = select_listed_warehouse(getConfig('DEFAULT_WAREHOUSE')); ?>
				<?php $qn = select_listed_quota($this->_user->quota_no); ?>
			<?php if(!empty($details)) : ?>
				<?php $parent = array(); ?>
				<?php $disabled = ""; ?>
				<?php foreach($details as $rs) : ?>
					<?php if($rs->type == 0) : ?>
		        <tr id="row-<?php echo $no; ?>">

							<input type="hidden" id="price-<?php echo $no; ?>" value="<?php echo $rs->Price; ?>" />
							<input type="hidden" id="sellPrice-<?php echo $no; ?>" value="<?php echo $rs->SellPrice; ?>" />
							<input type="hidden" id="sysSellPrice-<?php echo $no; ?>" value="<?php echo $rs->sysSellPrice; ?>" />
							<input type="hidden" class="line-num" id="line-num-<?php echo $no; ?>" value="<?php echo $no; ?>" />
							<input type="hidden" id="disc-amount-<?php echo $no; ?>" value="<?php echo $rs->discAmount; ?>"/>
							<input type="hidden" id="line-disc-amount-<?php echo $no; ?>" value="<?php echo $rs->totalDiscAmount; ?>" />
							<input type="hidden" id="totalDiscPercent-<?php echo $no; ?>" value="<?php echo $rs->DiscPrcnt; ?>" />
							<input type="hidden" id="line-total-<?php echo $no; ?>" value="<?php echo $rs->LineTotal; ?>" />
							<input type="hidden" id="vat-rate-<?php echo $no; ?>" value="<?php echo $rs->VatRate; ?>" />
							<input type="hidden" id="vat-amount-<?php echo $no; ?>" value="<?php echo $rs->VatAmount; ?>" />
							<input type="hidden" id="vat-total-<?php echo $no; ?>" value="<?php echo $rs->totalVatAmount; ?>" />
							<input type="hidden" id="sys-disc-label-<?php echo $no; ?>"  value="<?php echo $rs->sysDiscLabel; ?>" />
							<input type="hidden" id="uom-code-<?php echo $no; ?>" value="<?php echo $rs->UomCode; ?>" />
							<input type="hidden" class="disc-diff" id="disc-diff-<?php echo $no; ?>" value="<?php echo $rs->discDiff; ?>" />
							<input type="hidden" id="rule-id-<?php echo $no; ?>" value="<?php echo $rs->rule_id; ?>" />
							<input type="hidden" id="policy-id-<?php echo $no; ?>" value="<?php echo $rs->policy_id; ?>" />
							<input type="hidden" class="disc-error" id="disc-error-<?php echo $no; ?>" value="0" data-id="<?php echo $no; ?>" />
							<input type="hidden" id="<?php echo $rs->uid; ?>" data-id="<?php echo $no; ?>" value="<?php echo $no; ?>"/>
		          <input type="hidden" id="disc-type-<?php echo $no; ?>" value="<?php echo $rs->discType; ?>" />

		          <td class="middle text-center">
		            <input type="checkbox" class="ace del-chk" value="<?php echo $no; ?>"/>
		            <span class="lbl"></span>
		          </td>
							<td class="middle text-center">
								<select class="form-control input-sm toggle-text" id="type-1" onchange="toggleText($(this))" data-id="<?php echo $no; ?>">
		              <option value="0">-</option>
		              <option value="1">Text</option>
		            </select>
							</td>
		          <td class="middle text-center" id="img-<?php echo $no; ?>">
		          	<img src="<?php echo $rs->image; ?>" width="40" height="40" />
		          </td>
		          <td class="middle">
		            <input type="text" class="form-control input-sm item-code" data-id="<?php echo $no; ?>" id="itemCode-<?php echo $no; ?>" value="<?php echo $rs->ItemCode; ?>" <?php echo $disabled; ?> />
		          </td>
		          <td class="middle">
		            <input type="text" class="form-control input-sm item-name" data-id="<?php echo $no; ?>" id="itemName-<?php echo $no; ?>" value="<?php echo $rs->ItemName; ?>"  <?php echo $disabled; ?>/>
		          </td>

							<td class="middle">
								<select class="form-control input-sm whs" data-id="<?php echo $no; ?>" id="whs-<?php echo $no; ?>" onchange="getStock(<?php echo $no; ?>)">
									<option value=""></option>
									<?php echo select_order_warehouse($whsList, $rs->WhsCode); ?>
								</select>
							</td>

							<td class="middle">
		            <input type="text" class="form-control input-sm" id="instock-<?php echo $no; ?>" value="<?php echo $rs->instock; ?>" disabled/>
		          </td>

							<td class="middle">
								<select class="form-control input-sm quota" data-id="<?php echo $no; ?>" id="quota-<?php echo $no; ?>" onchange="getStock(<?php echo $no; ?>)">
									<option value=""></option>
									<?php echo select_order_quota($quotaList, $rs->QuotaNo); ?>
								</select>
							</td>

							<td class="middle">
		            <input type="text" class="form-control input-sm" id="team-<?php echo $no; ?>" value="<?php echo $rs->team; ?>" disabled/>
		          </td>

							<td class="middle">
		            <input type="text" class="form-control input-sm" id="commit-<?php echo $no; ?>" value="<?php echo $rs->commit; ?>" disabled/>
		          </td>

							<td class="middle">
		            <input type="text" class="form-control input-sm" id="available-<?php echo $no; ?>" value="<?php echo $rs->available; ?>" disabled/>
		          </td>

		          <td class="middle">
		            <input type="number" class="form-control input-sm text-right line-qty"
								data-id="<?php echo $no; ?>" id="line-qty-<?php echo $no; ?>"
								value="<?php echo $rs->Qty; ?>" onkeyup="recalAmount(<?php echo $no; ?>)" <?php echo $disabled; ?> />
		          </td>
		          <td class="middle">
		            <input type="text" class="form-control input-sm text-center" id="uom-<?php echo $no; ?>" value="<?php echo $rs->uom_name; ?>" disabled/>
		          </td>
		          <td class="middle">
		            <input type="text" class="form-control input-sm text-right number price"
								data-id="<?php echo $no; ?>" id="price-label-<?php echo $no; ?>"
								value="<?php echo number($rs->Price, 2); ?>" />
		          </td>

		          <td class="middle">
		            <input type="text" class="form-control input-sm text-right " id="disc-label-<?php echo $no; ?>"
								value="<?php echo $rs->discLabel; ?>" onchange="recalDiscount(<?php echo $no; ?>)"  <?php echo $disabled; ?>/>
		          </td>
		          <td class="middle">
		            <input type="text" class="form-control input-sm text-center" id="vat-code-<?php echo $no; ?>" value="<?php echo $rs->VatGroup; ?>" disabled/>
		          </td>

		          <td class="middle">
		            <input type="text" class="form-control input-sm text-right" id="sell-price-<?php echo $no; ?>" value="<?php echo number($rs->SellPrice, 4); ?>" readonly disabled>
		          </td>

		          <td class="middle">
		            <input type="text" class="form-control input-sm text-right number input-amount" id="total-label-<?php echo $no; ?>" value="<?php echo number($rs->LineTotal, 2); ?>" readonly disabled />
		          </td>
		        </tr>
					<?php else : ?>
						<tr id="row-<?php echo $no; ?>">
		          <td class="middle text-center">
		            <input type="checkbox" class="ace del-chk" value="<?php echo $no; ?>"/>
		            <span class="lbl"></span>
		          </td>
							<td class="middle text-center">
								<select class="form-control input-sm toggle-text" id="type-1" onchange="toggleText($(this))" data-id="<?php echo $no; ?>">
		              <option value="0">-</option>
		              <option value="1" selected>Text</option>
		            </select>
							</td>
							<td colspan="18">
						    <textarea id="text-<?php echo $no; ?>" class="autosize autosize-transition" style="height:150px; width:800px;"><?php echo $rs->LineText; ?></textarea>
						  </td>
		        </tr>
					<?php endif; ?>
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
	<input type="hidden" id="price-{{no}}" value="0" />
	<input type="hidden" id="sellPrice-{{no}}" value="0" />
	<input type="hidden" id="sysSellPrice-{{no}}" value="0" />
	<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
	<input type="hidden" id="disc-amount-{{no}}" value="0"/>
	<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
	<input type="hidden" id="totalDiscPercent-{{no}}" value="0.00" />
	<input type="hidden" id="line-total-{{no}}" value="0" />
	<input type="hidden" id="vat-rate-{{no}}" value="0" />
	<input type="hidden" id="vat-amount-{{no}}" value="0" />
	<input type="hidden" id="vat-total-{{no}}" value="0" />
	<input type="hidden" id="sys-disc-label-{{no}}"  value="0" />
	<input type="hidden" id="uom-code-{{no}}" value="" />
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
	<input type="hidden" id="rule-id-{{no}}" value="" />
	<input type="hidden" id="policy-id-{{no}}" value="" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
	<input type="hidden" class="free-item" id="free-item-{{no}}" value="0" data-id="{{no}}"
		data-rule="0" data-valid="0" data-picked="0" data-uid="{{uid}}" data-parent=""/>
	<input type="hidden" class="is-free" id="is-free-{{no}}" value="0" data-id="{{no}}" data-parent="" data-parentrow=""/>
	<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
  <input type="hidden" id="disc-type-{{no}}" value="{{discType}}" />


	<td class="middle text-center">
		<input type="checkbox" class="ace del-chk" value="{{no}}"/>
		<span class="lbl"></span>
	</td>
	<td class="middle text-center">
		<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
			<option value="0">-</option>
			<option value="1">Text</option>
		</select>
	</td>
	<td class="middle text-center" id="img-{{no}}"></td>
	<td class="middle">
		<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

	<td class="middle">
		<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock({{no}})">
			<option value=""></option>
			<?php echo $whs; ?>
		</select>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="instock-{{no}}" disabled/>
	</td>

	<td class="middle">
		<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock({{no}})">
			<option value=""></option>
			<?php echo $qn; ?>
		</select>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="team-{{no}}" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="commit-{{no}}" disabled/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm" id="available-{{no}}" disabled/>
	</td>

	<td class="middle">
		<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right disc" id="disc-label-{{no}}" onchange="recalDiscount({{no}})"/>
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


<script id="normal-template" type="text/x-handlebarsTemplate">
<input type="hidden" id="price-{{no}}" value="0" />
<input type="hidden" id="sellPrice-{{no}}" value="0" />
<input type="hidden" id="sysSellPrice-{{no}}" value="0" />
<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
<input type="hidden" id="disc-amount-{{no}}" value="0"/>
<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
<input type="hidden" id="totalDiscPercent-{{no}}" value="0.00" />
<input type="hidden" id="line-total-{{no}}" value="0" />
<input type="hidden" id="vat-rate-{{no}}" value="0" />
<input type="hidden" id="vat-amount-{{no}}" value="0" />
<input type="hidden" id="vat-total-{{no}}" value="0" />
<input type="hidden" id="sys-disc-label-{{no}}"  value="0" />
<input type="hidden" id="uom-code-{{no}}" value="" />
<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
<input type="hidden" id="rule-id-{{no}}" value="" />
<input type="hidden" id="policy-id-{{no}}" value="" />
<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
<input type="hidden" class="free-item" id="free-item-{{no}}" value="0" data-id="{{no}}"
	data-rule="0" data-valid="0" data-picked="0" data-uid="{{uid}}" data-parent=""/>
<input type="hidden" class="is-free" id="is-free-{{no}}" value="0" data-id="{{no}}" data-parent="" data-parentrow=""/>
<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
<input type="hidden" id="disc-type-{{no}}" value="{{discType}}" />

<td class="middle text-center">
	<input type="checkbox" class="ace del-chk" value="{{no}}"/>
	<span class="lbl"></span>
</td>
<td class="middle text-center">
	<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
		<option value="0" selected>-</option>
		<option value="1">Text</option>
	</select>
</td>
<td class="middle text-center" id="img-{{no}}"></td>
<td class="middle">
	<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
</td>
<td class="middle">
	<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
</td>

<td class="middle">
	<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock({{no}})">
		<option value=""></option>
		<?php echo $whs; ?>
	</select>
</td>

<td class="middle">
	<input type="text" class="form-control input-sm" id="instock-{{no}}" disabled/>
</td>

<td class="middle">
	<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock({{no}})">
		<option value=""></option>
		<?php echo $qn; ?>
	</select>
</td>

<td class="middle">
	<input type="text" class="form-control input-sm" id="team-{{no}}" disabled/>
</td>

<td class="middle">
	<input type="text" class="form-control input-sm" id="commit-{{no}}" disabled/>
</td>

<td class="middle">
	<input type="text" class="form-control input-sm" id="available-{{no}}" disabled/>
</td>

<td class="middle">
	<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
</td>
<td class="middle">
	<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
</td>
<td class="middle">
	<input type="text" class="form-control input-sm text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
</td>

<td class="middle">
	<input type="text" class="form-control input-sm text-right disc" id="disc-label-{{no}}" onchange="recalDiscount({{no}})"/>
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

</script>

<script id="text-template" type="text/x-handlebarsTemplate">
	<td class="middle text-center">
		<input type="checkbox" class="ace del-chk" value="{{no}}"/>
		<span class="lbl"></span>
	</td>
	<td class="middle text-center">
		<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
			<option value="0">-</option>
			<option value="1" selected>Text</option>
		</select>
	</td>
	<td colspan="18">
    <textarea id="text-{{no}}" class="autosize autosize-transition" style="height:150px; width:800px;"></textarea>
  </td>
</script>
