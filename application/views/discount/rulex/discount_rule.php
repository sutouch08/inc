<?php
$p_disabled = $rule->type === 'P' ? '' : 'disabled';
$n_disabled = $rule->type === 'N' ? '' : 'disabled';
$f_disabled = $rule->freeQty > 0 ? '' : 'disabled';
$checked = $rule->freeQty > 0 ? 'checked' : '';
?>

	<div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2">
					<span class="form-control left-label">
						<label>
							<input type="radio" class="ace disc-type" name="discType" value="N" onchange="toggleDiscType('N')" <?php echo is_checked('N', $rule->type); ?>>
							<span class="lbl">&nbsp;&nbsp; Net price</span>
						</label>
					</span>
				</div>
        <div class="col-sm-2 padding-5">
          <input type="number" class="form-control input-sm text-center price-input" id="net-price" value="<?php echo $rule->price; ?>" <?php echo $n_disabled; ?>/>
				</div>
				<div class="divider"></div>

        <div class="col-sm-2">
					<span class="form-control left-label margin-top-20">
						<label>
							<input type="radio" class="ace disc-type" name="discType" value="P" onchange="toggleDiscType('P')" <?php echo is_checked('P', $rule->type); ?> >
							<span class="lbl">&nbsp;&nbsp; Discount (%)</span>
						</label>
					</span>
				</div>
        <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="display-block">Step 1</label>
					<input type="number" class="form-control input-sm text-center disc-input" id="disc1" value="<?php echo $rule->disc1; ?>"  <?php echo $p_disabled; ?>/>
        </div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="display-block">Step 2</label>
					<input type="number" class="form-control input-sm text-center disc-input" id="disc2" value="<?php echo $rule->disc2; ?>" <?php echo $p_disabled; ?>/>
        </div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="display-block">Step 3</label>
					<input type="number" class="form-control input-sm text-center disc-input" id="disc3" value="<?php echo $rule->disc3; ?>" <?php echo $p_disabled; ?>/>
        </div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="display-block">Step 4</label>
					<input type="number" class="form-control input-sm text-center disc-input" id="disc4" value="<?php echo $rule->disc4; ?>" <?php echo $p_disabled; ?>/>
        </div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="display-block">Step 5</label>
					<input type="number" class="form-control input-sm text-center disc-input" id="disc5" value="<?php echo $rule->disc5; ?>" <?php echo $p_disabled; ?>/>
        </div>
				<div class="divider"></div>

        <div class="col-sm-2">
					<span class="form-control left-label margin-top-20">
						<label>
							<input type="checkbox" class="ace disc-type" name="free-item"  id="free-item" <?php echo $checked; ?> onchange="toggleFreeItem()">
							<span class="lbl">&nbsp;&nbsp; ของแถม</span>
						</label>
					</span>
				</div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5">
					<label class="not-show">จำนวน</label>
					<input type="number" class="form-control input-sm text-center free" id="free-qty" value="<?php echo $rule->freeQty; ?>"  <?php echo $f_disabled; ?>/>
        </div>
				<div class="col-lg-5 col-md-5 col-sm-5 padding-5" style="padding-top:5px;">
					<span class="form-control left-label margin-top-20">
					ชิ้น   จากรายการต่อไปนี้
					</span>
				</div>
				<div class="divider-hidden"></div>
				<div class="col-sm-2 not-show">
					<span class="form-control left-label">ของแถม2</span>
				</div>
        <div class="col-lg-7 col-md-7 col-sm-7 padding-5">
					<input type="text" class="form-control input-sm free" id="free-item-box" placeholder="รหัส/ชื่อสินค้า" <?php echo $f_disabled; ?> />
					<input type="hidden" id="temp-item-id" value="">
        </div>
				<div class="col-lg-1 col-md-1 col-sm-1 padding-5">
					<button type="button" class="btn btn-xs btn-primary btn-block free" onclick="addItemToList()" <?php echo $f_disabled; ?>><i class="fa fa-plus"></i> Add</button>
        </div>
				<div class="divider-hidden"></div>
				<div class="col-sm-2 not-show">
					<span class="form-control left-label">ของแถม3</span>
				</div>
				<div class="col-lg-10 col-md-10 col-sm-10 padding-5 table-responsive" style="max-height:300px;">
					<table class="table table-striped border-1">
						<thead>
							<tr>
								<th class="fix-width-40"></th>
								<th class="fix-width-150">SKU Code</th>
								<th class="min-width-250">Description</th>
								<th class="fix-width-60 text-center"><button type="button" class="btn btn-mini btn-danger btn-block" onclick="removeFreeItem()">Delete</button></th>
							</tr>
						</thead>
						<tbody id="freeItemList">
							<?php if(!empty($free_items)) : ?>
								<?php foreach($free_items as $item) : ?>
									<tr id="free-row-<?php echo $item->product_id; ?>">
										<td class="middle text-center">
											<label>
												<input type="checkbox" class="ace del-chk" value="<?php echo $item->product_id; ?>">
												<span class="lbl"></span>
											</label>
										</td>
										<td class="middle">
										<?php echo $item->code; ?>
										<input type="hidden" class="free-item-id" id="free-item-id-<?php echo $item->product_id; ?>" value="<?php echo $item->product_id; ?>">
										</td>
										<td class="middle" colspan="2"><?php echo $item->name; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
        </div>
				<div class="divider"></div>


        <div class="col-lg-2 col-md-2 col-sm-2">
					<span class="form-control left-label text-right">จำนวนขั้นต่ำ</span>
				</div>
        <div class="col-lg-2 col-md-2 col-sm-2">
					<input type="number" class="form-control input-sm text-center" id="min-qty" value="<?php echo $rule->minQty; ?>" />
        </div>
				<div class="divider-hidden"></div>


        <div class="col-lg-2 col-md-2 col-sm-2">
					<span class="form-control left-label text-right">มูลค่าขั้นต่ำ</span>
				</div>
        <div class="col-lg-2 col-md-2 col-sm-2">
					<input type="number" class="form-control input-sm text-center" id="min-amount" value="<?php echo $rule->minAmount;?>" />
        </div>
				<div class="divider-hidden"></div>
				<div class="divider-hidden"></div>
<div class="hide">
				<div class="col-lg-2 col-md-2 col-sm-2">
					<span class="form-control left-label text-right">รวมยอดได้</span>
				</div>
        <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5 margin-top-5">
					<label>
						<input type="radio" class="ace" name="canGroup"  value="1" <?php echo is_checked('1', $rule->canGroup); ?> />
						<span class="lbl">&nbsp;&nbsp;ได้</span>
					</label>
        </div>
				<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf padding-5 margin-top-5">
					<label>
						<input type="radio" class="ace" name="canGroup"  value="0" <?php echo is_checked('0', $rule->canGroup); ?>/>
						<span class="lbl">&nbsp;&nbsp;ไม่ได้</span>
					</label>
        </div>
				<div class="divider-hidden"></div>
</div>



				<div class="divider-hidden"></div>
				<div class="col-sm-2">&nbsp;</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-sm btn-success btn-block" onclick="saveDiscount()"><i class="fa fa-save"></i> บันทึก</button>
				</div>
    </div>


<script type="text/x-handlebarsTemplate" id="freeItemTemplate">
	<tr id="free-row-{{id}}">
		<td class="middle text-center"><label><input type="checkbox" class="ace del-chk" value="{{id}}"><span class="lbl"></span></label></td>
		<td class="middle">
		{{code}}
		<input type="hidden" class="free-item-id" id="free-item-id-{{id}}" value="{{id}}">
		</td>
		<td class="middle" colspan="2">{{name}}</td>
	</tr>
</script>
