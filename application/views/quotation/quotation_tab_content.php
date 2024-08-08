<div class="tab-pane fade active in" id="content">
  <div class="row" style="margin-left:0; margin-right:0;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" style="min-height:300px; max-height:600px; overflow:scroll; padding:0px; border-top:solid 1px #dddddd;">
      <table class="table table-bordered tableFixHead" style="min-width:1760px; margin-bottom:20px;">
        <thead>
          <tr class="font-size-10 freez">
            <th class="fix-width-40 middle text-center fix-no fix-header"></th>
            <th class="fix-width-40 middle text-center fix-add fix-header"></th>
            <th class="fix-width-40 middle text-center fix-img fix-header"></th>
            <th class="fix-width-40 middle text-center fix-text fix-header">Text</th>
            <th class="fix-width-150 middle text-center fix-item fix-header">Item Code</th>
            <th class="fix-width-150 middle text-center">Item Description.</th>
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
            <th class="fix-width-80 middle text-center">Qty in Whse</th>
            <th class="fix-width-60 middle text-center hide">Commited</th>
            <th class="fix-width-60 middle text-center hide">OnOrder</th>
          </tr>
        </thead>
        <tbody id="details-template">
          <?php $no = 1; ?>
          <?php $rows = 10; ?>
          <?php $select_warehouse = select_warehouse_code($whsCode); ?>
          <?php $select_uom = select_uom(); ?>
          <?php if(!empty($details)) : ?>
    				<?php foreach($details as $rs) : ?>
              <?php $bg = $rs->TreeType == 'S' ? 'father' : ($rs->TreeType == 'I' ? 'child' : ''); ?>
              <?php $error = $rs->SellPrice < $rs->Cost ? 'error' : ''; ?>
              <?php $dummy = $rs->ItemCode == 'FG-Dummy' ? 'dummy' : ''; ?>
              <?php $i_disable = $rs->TreeType == 'I' ? 'disabled' : ''; ?>
              <?php $s_disable = $rs->TreeType == 'S' ? 'disabled' : ''; ?>
              <tr id="row-<?php echo $no; ?>" data-no="<?php echo $no; ?>" class="rows <?php echo $bg; ?> <?php echo $error; ?> <?php echo $dummy; ?>">
                <input type="hidden" class="line-num" id="line-num-<?php echo $no; ?>" value="<?php echo $no; ?>" />
                <input type="hidden" id="stdPrice-<?php echo $no; ?>" value="<?php echo $rs->stdPrice; ?>" />
                <input type="hidden" id="price-<?php echo $no; ?>" value="<?php echo $rs->Price; ?>" />
                <input type="hidden" id="cost-<?php echo $no; ?>" value="<?php echo $rs->Cost; ?>" />
                <input type="hidden" id="bCost-<?php echo $no; ?>" value="<?php echo $rs->SellPrice < $rs->Cost ? 1 : 0; ?>" />
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
                <input type="hidden" class="disc-diff" id="disc-diff-<?php echo $no; ?>" data-no="<?php echo $no; ?>" value="<?php echo $rs->discDiff; ?>" />
                <input type="hidden" class="disc-error" id="disc-error-<?php echo $no; ?>" value="0" data-id="<?php echo $no; ?>" />
                <input type="hidden" id="uid-<?php echo $no; ?>" class="uid" data-no="<?php echo $no; ?>" value="<?php echo $rs->uid; ?>" />
                <input type="hidden" id="tree-type-<?php echo $no; ?>" value="<?php echo $rs->TreeType; ?>" />
                <input type="hidden" id="father-uid-<?php echo $no; ?>" value="<?php echo $rs->father_uid; ?>" />
                <input type="hidden" id="is-blank-<?php echo $no; ?>" value="0" data-no="<?php echo $no; ?>"/>
                <?php if($rs->TreeType == 'I') : ?>
                <input type="hidden" class="child-<?php echo $rs->father_uid; ?>" data-no="<?php echo $no; ?>" value="<?php echo $rs->father_uid; ?>" />
                <?php endif; ?>
                <td class=" text-center fix-no no" scope="row"><?php echo $no; ?></td>
                <td class=" text-center fix-add" scope="row">
                  <?php if($rs->TreeType != 'I') : ?>
                    <a class="pointer" href="javascript:insertBefore(<?php echo $no; ?>)" title="Insert before"><i class="fa fa-plus"></i></a>
                  <?php endif; ?>
                </td>
                <td class=" text-center fix-img" scope="row">
                  <?php if($rs->TreeType != 'I') : ?>
                    <a class="pointer" href="javascript:removeRow(<?php echo $no; ?>)" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
                  <label class="hide" id="chk-label-<?php echo $no; ?>" class="">
                    <input type="checkbox" class="ace del-chk" value="<?php echo $no; ?>"/>
                    <span class="lbl"></span>
                  </label>
                  <?php endif; ?>
                </td>
                <td class=" text-center fix-text" scope="row">
                  <a class="pointer <?php echo empty($rs->LineText) ? '' : 'hide'; ?>" id="add-text-<?php echo $no; ?>" href="javascript:insertTextRow(<?php echo $no; ?>)" title="Insert text row"><i class="fa fa-plus-square-o fa-lg"></i></a>
                </td>
                <td class=" fix-item" scope="row">
                  <input type="text" class="form-control input-xs item-code" data-id="<?php echo $no; ?>" id="itemCode-<?php echo $no; ?>" value="<?php echo $rs->ItemCode; ?>" <?php echo $i_disable; ?> />
                </td>
                <td class="">
                  <input type="text" class="form-control input-xs item-name" data-id="<?php echo $no; ?>" id="itemName-<?php echo $no; ?>" value="<?php echo $rs->ItemName; ?>"  />
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs item-detail" data-id="<?php echo $no; ?>" id="itemDetail-<?php echo $no; ?>" value="<?php echo $rs->Description; ?>"  />
                </td>

                <td class="">
                  <input type="number" class="form-control input-xs text-right line-qty <?php echo $rs->father_uid; ?>"
                  data-id="<?php echo $no; ?>" id="line-qty-<?php echo $no; ?>"
                  value="<?php echo $rs->Qty; ?>" onkeyup="recalAmount(<?php echo $no; ?>)" <?php echo $i_disable; ?>/>
                </td>

                <td class="">
                  <select class="form-control input-xs" id="uom-<?php echo $no; ?>" <?php echo $rs->ItemCode == 'FG-Dummy' ? '' : 'disabled'; ?>>
                    <option value=""></option>
                    <?php echo select_uom($rs->UomEntry); ?>
                  </select>
                </td>

                <td class=" hide">
                  <input type="text" class="form-control input-xs text-right" id="stdPrice-label-<?php echo $no; ?>" value="<?php echo number($rs->stdPrice, 2); ?>" disabled/>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right number price"
                  data-id="<?php echo $no; ?>" id="price-label-<?php echo $no; ?>"
                  value="<?php echo number($rs->Price, 2); ?>" <?php echo $s_disable; ?>/>
                </td>

                <td class=" hide">
                  <input type="text" class="form-control input-xs text-right " id="sys-disc-label-<?php echo $no; ?>"
                  value="<?php echo $rs->sysDisc; ?>" disabled/>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc1-<?php echo $no; ?>"
                  value="<?php echo $rs->disc1; ?>" onchange="recalDiscount(<?php echo $no; ?>)" <?php echo $s_disable; ?> />
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc2-<?php echo $no; ?>"
                  value="<?php echo $rs->disc2; ?>" onchange="recalDiscount(<?php echo $no; ?>)" <?php echo $s_disable; ?>/>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc3-<?php echo $no; ?>"
                  value="<?php echo $rs->disc3; ?>" onchange="recalDiscount(<?php echo $no; ?>)" <?php echo $s_disable; ?>/>
                </td>

                <td class="">
                  <select id="whs-<?php echo $no; ?>" class="form-control input-xs" onchange="getStock(<?php echo $no; ?>)">
                    <option value="">Please select</option>
                    <?php echo select_warehouse_code($rs->WhsCode); ?>
                  </select>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-center" id="vat-code-<?php echo $no; ?>" value="<?php echo $rs->VatGroup; ?>" disabled/>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right" id="sell-price-<?php echo $no; ?>" value="<?php echo number($rs->SellPrice, 2); ?>" readonly disabled>
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right number input-amount" id="total-label-<?php echo $no; ?>" value="<?php echo number($rs->LineTotal, 2); ?>" readonly disabled />
                </td>

                <td class="">
                  <input type="text" class="form-control input-xs text-right" id="onhand-<?php echo $no; ?>" value="<?php echo number($rs->OnHand); ?>" disabled/>
                </td>

                <td class="hide">
                  <input type="text" class="form-control input-xs text-right" id="commited-<?php echo $no; ?>" value="<?php echo number($rs->Commited); ?>" disabled/>
                </td>

                <td class="hide">
                  <input type="text" class="form-control input-xs text-right" id="onorder-<?php echo $no; ?>" value="<?php echo number($rs->OnOrder); ?>" disabled/>
                </td>
              </tr>
              <?php $parent_no = $no; ?>
              <?php $no++; ?>

              <?php if(! empty($rs->LineText)) : ?>
                <tr id="row-<?php echo $no; ?>" data-no="<?php echo $no; ?>" class="rows">
                  <td class=" text-center fix-no no" scope="row"><?php echo $no; ?></td>
                  <td class=" text-center fix-add" scope="row"></td>
                  <td class=" text-center fix-img" scope="row">
                    <a class="pointer" href="javascript:removeTextRow(<?php echo $no; ?>, <?php echo $parent_no; ?>)" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
                  </td>
                  <td class="text-center fix-text" scope="row"></td>
                  <td class="text-center fix-item" scope="row"></td>
                  <td class="" colspan="2" >
                    <textarea id="text-<?php echo $rs->uid; ?>"
                      data-no="<?php echo $no; ?>"
                      data-parent="<?php echo $parent_no; ?>"
                      class="autosize autosize-transition form-control"
                      style="min-height:30px; border:0px;"><?php echo $rs->LineText; ?></textarea>
                  </td>
                  <td colspan="10"></td>
                </tr>
                <?php $no++;  ?>
                <?php $rows--; ?>
              <?php endif; ?>
              <?php $rows--; ?>
            <?php endforeach; ?>
    			<?php endif; ?>


          <?php while($rows > 0) : ?>
            <tr id="row-<?php echo $no; ?>" data-no="<?php echo $no; ?>" class="rows">
              <input type="hidden" class="line-num" id="line-num-<?php echo $no; ?>" value="<?php echo $no; ?>" />
              <input type="hidden" id="stdPrice-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="price-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="cost-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="bCost-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="sellPrice-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="sysSellPrice-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="disc-amount-<?php echo $no; ?>" value="0"/>
              <input type="hidden" id="line-disc-amount-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="totalDiscPercent-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="line-total-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="line-sys-total-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="vat-rate-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="vat-amount-<?php echo $no; ?>" value="0" />
              <input type="hidden" id="vat-total-<?php echo $no; ?>" value="0" />
              <input type="hidden" class="disc-diff" id="disc-diff-<?php echo $no; ?>" data-no="<?php echo $no; ?>" value="0" />
              <input type="hidden" class="disc-error" id="disc-error-<?php echo $no; ?>" value="0" data-id="<?php echo $no; ?>" />
              <input type="hidden" id="uid-<?php echo $no; ?>" class="uid" data-no="<?php echo $no; ?>" value="" />
              <input type="hidden" id="tree-type-<?php echo $no; ?>" value="N" />
              <input type="hidden" id="father-uid-<?php echo $no; ?>" value="" />
              <input type="hidden" id="is-blank-<?php echo $no; ?>" value="1" data-no="<?php echo $no; ?>"/>

              <td class=" text-center fix-no no" scope="row"><?php echo $no; ?></td>
              <td class=" text-center fix-add" scope="row">
                <a class="pointer" href="javascript:insertBefore(<?php echo $no; ?>)"><i class="fa fa-plus"></i></a>
              </td>
              <td class=" text-center fix-img" scope="row">
                <a class="pointer" href="javascript:removeRow(<?php echo $no; ?>)" title="Remove this row"><i class="fa fa-trash fa-lg red"></i></a>
              </td>
              <td class=" text-center fix-text" scope="row">
                <a class="pointer hide" id="add-text-<?php echo $no; ?>" href="javascript:insertTextRow(<?php echo $no; ?>)" title="Insert text row"><i class="fa fa-plus-square-o fa-lg"></i></a>
              </td>
              <td class=" fix-item" scope="row">
                <input type="text" class="form-control input-xs item-code" data-id="<?php echo $no; ?>" id="itemCode-<?php echo $no; ?>" value=""  />
              </td>
              <td class="">
                <input type="text" class="form-control input-xs item-name" data-id="<?php echo $no; ?>" id="itemName-<?php echo $no; ?>" value=""  />
              </td>

              <td class="">
                <input type="text" class="form-control input-xs item-detail" data-id="<?php echo $no; ?>" id="itemDetail-<?php echo $no; ?>" value=""  />
              </td>

              <td class="">
                <input type="number" class="form-control input-xs text-right line-qty"
                data-id="<?php echo $no; ?>" id="line-qty-<?php echo $no; ?>"
                value="" onkeyup="recalAmount(<?php echo $no; ?>)" />
              </td>
              <td class="">
                <select class="form-control input-xs" id="uom-<?php echo $no; ?>" disabled>
                  <option value=""></option>
                  <?php echo $select_uom; ?>
                </select>
              </td>

              <td class=" hide">
                <input type="text" class="form-control input-xs text-right" id="stdPrice-label-<?php echo $no; ?>" value="" disabled/>
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right number price" data-id="<?php echo $no; ?>" id="price-label-<?php echo $no; ?>" value="" />
              </td>

              <td class=" hide">
                <input type="text" class="form-control input-xs text-right " id="sys-disc-label-<?php echo $no; ?>"
                value="" disabled/>
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc1-<?php echo $no; ?>"
                value="" onchange="recalDiscount(<?php echo $no; ?>)" />
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc2-<?php echo $no; ?>"
                value="" onchange="recalDiscount(<?php echo $no; ?>)" />
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right disc disc-<?php echo $no; ?>" id="disc3-<?php echo $no; ?>"
                value="" onchange="recalDiscount(<?php echo $no; ?>)" />
              </td>

              <td class="">
                <select id="whs-<?php echo $no; ?>" class="form-control input-xs" onchange="getStock(<?php echo $no; ?>)">
                  <option value="">Please select</option>
                  <?php echo $select_warehouse; ?>
                </select>
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-center" id="vat-code-<?php echo $no; ?>" value="" disabled/>
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right" id="sell-price-<?php echo $no; ?>" value="" readonly disabled>
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right number input-amount" id="total-label-<?php echo $no; ?>" value="" readonly disabled />
              </td>

              <td class="">
                <input type="text" class="form-control input-xs text-right" id="onhand-<?php echo $no; ?>" value="" disabled/>
              </td>

              <td class=" hide">
                <input type="text" class="form-control input-xs text-right" id="commited-<?php echo $no; ?>" value="" disabled/>
              </td>

              <td class=" hide">
                <input type="text" class="form-control input-xs text-right" id="onorder-<?php echo $no; ?>" value="" disabled/>
              </td>
            </tr>
            <?php $no++; ?>
            <?php $rows--; ?>
          <?php endwhile; ?>
        </tbody>
      </table>
      <input type="hidden" id="row-no" value="<?php echo $no; ?>" />
    </div>
    <div class="divider-hidden"></div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
      <button type="button" class="btn btn-xs btn-info" onclick="addRow()">Add Row</button>
      <button type="button" class="btn btn-xs btn-warning hide" onclick="removeRows()">Delete Row</button>
    </div>
  </div>
</div>
