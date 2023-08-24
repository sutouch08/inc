<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?> </h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
  	<p class="pull-right top-p">
  	<?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
		<?php endif; ?>
  	</p>
  </div>
</div><!-- End Row -->
<hr class="">
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <label>Employee</label>
    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" />
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th class="fix-width-120"></th>
					<th class="fix-width-80 middle text-center">#</th>
					<th class="min-width-350 middle">Employee</th>
				</tr>
			</thead>
			<tbody>
			<?php if( ! empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle">
							<button type="button" class="btn btn-mini btn-info" onclick="showSignature(<?php echo $rs->emp_id; ?>)"><i class="fa fa-eye"></i></button>
							<?php if($this->pm->can_edit) : ?>
								<button type="button" class="btn btn-mini btn-warning" onclick="getEdit(<?php echo $rs->emp_id; ?>)"><i class="fa fa-pencil"></i></button>
							<?php endif; ?>
							<?php if($this->pm->can_delete) : ?>
								<button type="button" class="btn btn-mini btn-danger" onclick="confirmDelete(<?php echo $rs->emp_id; ?>, '<?php echo $rs->firstName.' '.$rs->lastName; ?>')">
                  <i class="fa fa-trash"></i>
                </button>
							<?php endif; ?>

              <input type="hidden" id="image-data-<?php echo $rs->emp_id; ?>" value="<?php echo $rs->image_data; ?>" data-emp="<?php echo $rs->firstName.' '.$rs->lastName; ?>"/>
						</td>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->firstName.' '.$rs->lastName; ?></td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="blue text-center">Add Signature</h4>
			</div>
			<form class="no-margin" id="imageForm">
				<div class="modal-body">
          <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12">
              <div class="input-group">
                <span class="input-group-addon">Employee</span>
                <select class="width-100" id="emp_id">
                  <option value="">-Select Employee-</option>
                  <?php echo select_employee(); ?>
                </select>
              </div>
            </div>
            <div class="divider"> </div>
          </div>

          <div class="row">
            <div style="width:75%; margin:auto;">
              <label id="btn-select-file" class="ace-file-input ace-file-multiple">
                <input type="file" name="image" id="image" accept="image/*" style="display:none;" />
                <span class="ace-file-container" data-title="Click to choose new Image">
                  <span class="ace-file-name" data-title="No File ...">
                    <i class=" ace-icon ace-icon fa fa-picture-o"></i>
                  </span>
                </span>
              </label>
              <div id="block-image" style="position: relative; min-width: 100px; max-width: 300px; margin: auto; opacity:0;">
                <div id="previewImg" class="center"></div>
                <span onClick="removeFile()" style="position:absolute; right:0px; top:1px; cursor:pointer; color:red;">
                  <i class="fa fa-times fa-2x"></i>
                </span>
              </div>
            </div>
          </div>
				</div>
				<div class="modal-footer center">
					<button type="button" class="btn btn-sm btn-success" onclick="add()"><i class="ace-icon fa fa-check"></i> Submit</button>
					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<input type="hidden" id="img-data" value="" />

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="blue text-center">Signature</h4>
			</div>
			<form class="no-margin">
				<div class="modal-body">

          <div class="row">
            <div style="width:75%; margin:auto;">
              <div id="block-image" style="position: relative; min-width: 100px; max-width: 300px; margin: auto;">
                <div id="view-image" class="center">

                </div>
              </div>
            </div>
          </div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="viewxModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:800px; max-width:95%; margin-left:auto; margin-right:auto;">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
       </div>
       <div class="modal-body">
         <div class="row" style="max-height:75vh; overflow:auto;">
           <table class="table table-striped border-1">
             <thead>
               <tr>
                 <th class="fix-width-40 text-center">
                   <label>
                     <input type="checkbox" class="ace" id="select-all" onchange="selectAll()" />
                     <span class="lbl"></span>
                   </label>
                 </th>
                 <th class="fix-width-150">PEA NO (เก่า)</th>
                 <th class="fix-width-150">PEA NO (ใหม่)</th>
                 <th class="fix-width-100 text-center">เขต</th>
								 <th class="fix-width-100 text-center">วันที่ติดตั้ง</th>
                 <th class="min-width-100">ผู้ติดตั้ง</th>
               </tr>
             </thead>
             <tbody id="items-table">

             </tbody>
           </table>
         </div>
       </div>
       <div class="modal-footer">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-right">
           <button type="button" class="btn btn-sm btn-info btn-100" onclick="addToTransfer()">เพิ่มเข้าเอกสาร</button>
         </div>
       </div>
		</div>
	</div>
</div>

<script>
  $('#emp_id').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/users/signature.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
