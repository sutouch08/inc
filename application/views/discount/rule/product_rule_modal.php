
<div class="modal fade" id="pd-cat-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Category</h4>
      </div>
      <div class="modal-body" id="pd-cat-body">
        <div class="row" style="margin-left:0px;">
          <div class="col-sm-12">
    <?php if(! empty($product_categorys)) : ?>
      <?php foreach($product_categorys as $rs) : ?>
        <?php $se = isset($pdCategory[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-pd-cat"
								name="chk-pd-cat-<?php echo $rs->id; ?>"
								id="chk-pd-cat-<?php echo $rs->id; ?>"
								value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
								<span class="lbl">&nbsp;<?php echo $rs->name; ?></span>
              </label>
      <?php endforeach; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="pd-type-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Type</h4>
      </div>
      <div class="modal-body" id="pd-type-body">
        <div class="row" style="margin-left:0px;">
          <div class="col-sm-12">
				    <?php if( ! empty($product_types)) : ?>
				      <?php foreach($product_types as $rs) : ?>
								<?php $se = isset($pdType[$rs->id]) ? 'checked' : ''; ?>
				            <label class="display-block">
				              <input type="checkbox"
											class="ace chk-pd-type"
											name="chk-pd-type-<?php echo $rs->id; ?>"
											id="chk-pd-type-<?php echo $rs->id; ?>"
											value="<?php echo $rs->id; ?>" />
				              <span class="lbl"><?php echo $rs->name; ?></span>
				             </label>
				      <?php endforeach; ?>
				    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="pd-brand-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เลือกยี่ห้อสินค้า</h4>
      </div>
      <div class="modal-body" id="pd-brand-body">
        <div class="row" style="margin-left:0px;">
          <div class="col-sm-12">

    <?php if( ! empty($product_brands)) : ?>
      <?php foreach($product_brands as $rs) : ?>
        <?php $se = isset($pdBrand[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-pd-brand"
								name="chk-pd-brand-<?php echo $rs->id; ?>"
								id="chk-pd-brand-<?php echo $rs->id; ?>"
								value="<?php echo $rs->id; ?>" <?php echo $se; ?> />
								<span class="lbl"><?php echo $rs->name; ?></span>
              </label>
      <?php endforeach; ?>
    <?php endif;?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>
