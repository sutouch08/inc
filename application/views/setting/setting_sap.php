	<form id="sapForm" method="post" action="<?php echo $this->home; ?>/update_config">
  	<div class="row">
    	<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
        <span class="form-control left-label">Default Currency</span>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
				<label class="visible-xs">Currency</label>
        <input type="text" class="form-control input-sm" name="CURRENCY"  value="<?php echo $DEFAULT_CURRENCY; ?>" />
      </div>
      <div class="divider-hidden hidden-xs"></div>

			<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
        <span class="form-control left-label">Default VAT Code</span>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
				<label class="visible-xs">VAT Code</label>
        <input type="text" class="form-control input-sm" name="SALE_VAT_CODE" value="<?php echo $SALE_VAT_CODE; ?>" />
      </div>
      <div class="divider-hidden hidden-xs"></div>

      <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
        <span class="form-control left-label">Default VAT Rate</span>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
				<label class="visible-xs">VAT Rate</label>
        <input type="text" class="form-control input-sm" name="SALE_VAT_RATE" value="<?php echo $SALE_VAT_RATE; ?>" />
      </div>
      <div class="divider-hidden hidden-xs"></div>


			<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
        <span class="form-control left-label">Default Warehouse</span>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-8">
				<label class="visible-xs">Warehouse</label>
      	<select class="width-100" name="DEFAULT_WAREHOUSE">
					<?php echo select_warehouse($DEFAULT_WAREHOUSE); ?>
				</select>
      </div>
      <div class="divider-hidden hidden-xs"></div>
			<div class="divider-hidden  hidden-xs"></div>
			<div class="divider-hidden  hidden-xs"></div>
			<div class="divider-hidden  hidden-xs"></div>

			<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
      <div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 hidden-xs">
        <button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('sapForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>

      </div>

			<div class="col-xs-4 visible-xs">
				<label class="visible-xs not-show">save</label>
        <button type="button" class="btn btn-xs btn-success btn-100" onClick="updateConfig('sapForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>
      </div>
			<?php endif; ?>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
