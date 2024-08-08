<!--  Add New Address Modal  --------->
<div class="modal fade" id="sqModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:800px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >Load Quotation</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <label>Date</label>
        		<div class="input-daterange input-group width-100">
        			<input type="text" class="width-50 text-center from-date" name="from_date" id="fDate" value="" />
        			<input type="text" class="width-50 text-center" name="to_date" id="tDate" value="" />
        		</div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
            <label>Customer</label>
            <input type="text" class="form-control input-sm" id="sq-customer" placeholder="filter by customer"/>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
            <label>SQ No.</label>
            <input type="number" class="form-control input-sm" id="sq-code" placeholder="filter by SQ No."/>
          </div>
          <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-3">
            <label class="display-block not-show">btn</label>
            <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSqList()">OK</button>
          </div>
          <div class="divider">

          </div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sq-detail" style="padding-left: 0; padding-right: 0; min-height:100px; max-height:300px; overflow:auto;">

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-primary btn-100" onclick="addSqToOrder()">Add</button>
        <button type="button" class="btn btn-xs btn-default btn-100" onclick="closeModal('sqModal')">Close</button>
      </div>
    </div>
  </div>
</div>

<script id="sq-template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered tableFixHead">
  <thead>
    <tr class="freez">
      <th class="fix-width-40"></th>
      <th class="fix-width-100">Date</th>
      <th class="fix-width-100">SQ No.</th>
      <th class="fix-width-120">Web No.</th>
      <th class="fix-width-120">Customer</th>
      <th class="min-width-200"></th>
    </tr>
  </thead>
  <tbody>
  {{#each this}}
    <tr>
      <td class="text-center">
        <input type="checkbox" class="sq-chk" id="sq-{{DocEntry}}" value="{{DocEntry}}">
      </td>
      <td class="">{{DocDate}}</td>
      <td>{{DocNum}}</td>
      <td>{{U_WEBORDER}}</td>
      <td>{{CardCode}}</td>
      <td>{{CardName}}</td>
    </tr>
    {{/each}}
  </tbody>
</table>
</script>
