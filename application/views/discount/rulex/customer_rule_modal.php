
<div class="modal fade" id="cust-group-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Customer Group</h4>
      </div>
      <div class="modal-body" id="cust-group-body">
        <div class="row">
          <div class="col-sm-12">
    <?php if( ! empty($customer_groups)) : ?>
      <?php foreach($customer_groups as $rs) : ?>
        <?php $se = isset($custGroup[$rs->code]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-group"
								id="chk-group-<?php echo $rs->code; ?>"
								value="<?php echo $rs->code; ?>" <?php echo $se; ?> />
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


<div class="modal fade" id="cust-type-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Customer Type</h4>
      </div>
      <div class="modal-body" id="cust-type-body">
        <div class="row">
          <div class="col-sm-12">
    <?php if(! empty($customer_types)) : ?>
      <?php foreach($customer_types as $rs) : ?>
        <?php $se = isset($custType[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-type"
								id="chk-type-<?php echo $rs->id; ?>"
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




<div class="modal fade" id="cust-region-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Customer Region</h4>
      </div>
      <div class="modal-body" id="cust-region-body">
        <div class="row">
          <div class="col-sm-12">
    <?php if( ! empty($customer_regions)) : ?>
      <?php foreach($customer_regions as $rs) : ?>
        <?php $se = isset($custRegion[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-region"
								id="chk-region-<?php echo $rs->id; ?>"
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




<div class="modal fade" id="cust-area-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เขตลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-area-body">
        <div class="row">
          <div class="col-sm-12">
    <?php if( ! empty($customer_areas)) : ?>
      <?php foreach($customer_areas as $rs) : ?>
        <?php $se = isset($custArea[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class="ace chk-area"
								id="chk-area-<?php echo $rs->id; ?>"
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




<div class="modal fade" id="cust-grade-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:300px;">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">เกรดลูกค้า</h4>
      </div>
      <div class="modal-body" id="cust-grade-body">
        <div class="row">
          <div class="col-sm-12">
    <?php if( ! empty($customer_grades)) : ?>
      <?php foreach($customer_grades as $rs) : ?>
        <?php $se = isset($custGrade[$rs->id]) ? 'checked' : ''; ?>
              <label class="display-block">
                <input type="checkbox"
								class=" ace chk-grade"
								id="chk-grade-<?php echo $rs->id; ?>"
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
