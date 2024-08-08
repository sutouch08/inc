var isRequesting = 0;

function saveAsDraft() {
	$('#is_draft').val(1);
	saveAdd();
}



function saveAdd() {
	$('.btn-save').attr('disabled', 'disabled');

	setTimeout(function() {
		let mustApprove = 0;
		let max_diff = 0;
		let belowCost = 0;
		let totalAmount = parseDefault(parseFloat($('#totalAmount').val()), 0);
		let max_discount = parseDefault(parseFloat($('#max_discount').val()), 0);
		let max_amount = parseDefault(parseFloat($('#max_amount').val()), 0);

		let docTotal = parseDefault(parseFloat($('#docTotal').val()), 0);
		let sysTotal = parseDefault(parseFloat($('#sysTotalAmount').val()), 0);
		let billDisc = parseDefault(parseFloat($('#discPrcnt').val()), 0);
		let billDiscAmount = parseDefault(parseFloat($('#discAmount').val()), 0);

		$('.disc-diff').each(function() {
			let no = $(this).data('no');
			let bCost = parseDefault(parseInt($('#bCost-'+no).val()), 0);
			belowCost += bCost;

			if($(this).val() > 0) {
				let diff = parseDefault(parseFloat($(this).val()), 0);
				max_diff =  diff > max_diff ? diff : max_diff;
			}
		});

		if(billDisc > 0 && billDiscAmount > 0 && sysTotal > 0) {
			let exDisc = (billDiscAmount/sysTotal) * 100;
			max_diff += exDisc;
		}

		if(max_diff > 0) {
			if(max_diff >= max_discount) {
				mustApprove++;
			}
		}

		if(mustApprove == 0) {
			mustApprove = docTotal >= max_amount ? 1 : 0;
		}

		var ds = {
			//---- Right column
			'isDraft' : $('#is_draft').val(),
			'SlpCode' : $('#sale_id').val(),
			'CardCode' : $.trim($('#CardCode').val()),  //****** required
			'CardName' : $('#CardName').val(),
			'CntctCode' : $('#CntctCode').val(),
			'ContactPerson' : $('#contact').val(),
			'NumAtCard' : $('#NumAtCard').val(),
			'Attn1' : $('#Attn1').val(),
			'Attn2' : $('#Attn2').val(),
			'Type' : $('#Type').val(),
			'Project' : $('#Project').val(),
			'Phone' : $('#phone').val(),
			'Payment' : $('#payment').val(),
			'OwnerCode' : $('#owner').val(),
			'ShipToCode' : $('#shipToCode').val(),
			'ShipTo' : $('#ShipTo').val(),
			//--- right Column
			'DocDate' : $('#DocDate').val(), //****** required
			'DocDueDate' : $('#ShipDate').val(), //****** required
			'TextDate' : $('#TextDate').val(), //****** required
			'SQNO' : $('#SQNO').val(),
			'PayToCode' : $('#billToCode').val(),
			'BillTo' : $('#BillTo').val(),
			//---- footer
			'comments' : $.trim($('#comments').val()),
			'discPrcnt' : billDisc,
			'disAmount' : billDiscAmount,
			'roundDif' : parseDefault(parseFloat($('#roundDif').val()), 0),
			'tax' : parseDefault(parseFloat($('#tax').val()), 0), //-- VatSum
			'docTotal' : docTotal,
			'sysTotal' : sysTotal,
			'mustApprove' : mustApprove > 0 ? 1 : 0,
			'maxDiff' : max_diff,
			'VatGroup' : $('#vat_code').val(),
			'VatRate' : $('#vat_rate').val(),
			'sale_team' : $('#sale_team').val(),
			'Address' : {
				'bAddress' : $('#billToCode').val(),
				'bAddress2' : $('#billToCode option:selected').data('name'),
				'sAddress' : $('#shipToCode').val(),
				'sAddress2' : $('#shipToCode option:selected').data('name'),
				'sStreet' : $('#Street-s').val(),
				'bStreet' : $('#Street-b').val(),
				'bStreetNo' : $('#StreetNo-b').val(),
				'bBlock' : $('#Block-b').val(),
				'bCity' : $('#City-b').val(),
				'bCounty' : $('#County-b').val(),
				'bZipCode' : $('#ZipCode-b').val(),
				'bCountry' : $('#Country-b').val(),
				'sStreetNo' : $('#StreetNo-s').val(),
				'sBlock' : $('#Block-s').val(),
				'sCity' : $('#City-s').val(),
				'sCounty' : $('#County-s').val(),
				'sZipCode' : $('#ZipCode-s').val(),
				'sCountry' : $('#Country-s').val()
			}
		}

		// console.log(ds);
		// return false;

		//--- check required parameter
		if(ds.CardCode.length === 0) {
			swal("กรุณาระบุลูกค้า");
			$('#CardCode').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#CardCode').removeClass('has-error');
		}

		if(!isDate(ds.DocDate)) {
			swal("Invalid Posting Date");
			$('#DocDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#DocDate').removeClass('has-error');
		}


		if(!isDate(ds.DocDueDate)) {
			swal("Invalid Delivery Date");
			$('#DocDueDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#DocDueDate').removeClass('has-error');
		}

		if(!isDate(ds.TextDate)) {
			swal("Invalid Document Date");
			$('#TextDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#TextDate').removeClass('has-error');
		}

		var disc_error = 0;
		//--- check discount
		$('.disc-error').each(function() {
			no = $(this).data('id');
			if($(this).val() == 1) {
				$('#disc-error-'+no).addClass('has-error');
				disc_error++;
			}
			else {
				$('#disc-error-'+no).removeClass('has-error');
			}
		});

		if(disc_error > 0) {
			swal({
				title:'Invalid Discount',
				type:'error'
			});

			$('.btn-save').removeAttr('disabled');

			return false;
		}

		if(ds.discPrcnt < 0 || ds.discPrcnt > 100) {
			swal({
				title:"Invalid bill discount",
				type:'error'
			});

			$('.btn-save').removeAttr('disabled');

			return false;
		}


		//---- get rows details
		var count = 0;
		var details = [];
		var lineNum = 0;
		var uomError = 0;

		$('.item-code').each(function() {
			let no = $(this).data('id');
			let itemCode = $('#itemCode-'+no).val();
			if(itemCode.length) {
				//--- ถ้ามีการระบุข้อมูล
				let uomEntry = $('#uom-'+no).val();
				let uid = $('#uid-'+no).val();

				var row = {
					"LineNum" : lineNum,
					"BaseRef" : $('#baseRef-'+no).val(),
					"BaseEntry" : $('#baseEntry-'+no).val(),
					"BaseLine" : $('#baseLine-'+no).val(),
					"ItemCode" : itemCode,
					"ItemName" : $('#itemName-'+no).val(),
					"Description" : $('#itemDetail-'+no).val(),
					"Cost" : $('#cost-'+no).val(),
					"Price" : $('#price-'+no).val(),
					"stdPrice" : $('#stdPrice-'+no).val(),
					"SellPrice" : $('#sellPrice-'+no).val(),
					"sysSellPrice" : $('#sysSellPrice-'+no).val(),
					"Quantity" : $('#line-qty-'+no).val(),
					"UomEntry" : uomEntry,
					"sysDiscLabel" : $('#sys-disc-label-'+no).val(),
					"disc1" : $('#disc1-'+no).val(),
					"disc2" : $('#disc2-'+no).val(),
					"disc3" : $('#disc3-'+no).val(),
					"discAmount" : $('#disc-amount-'+no).val(),
					"totalDiscAmount" : $('#line-disc-amount-'+no).val(),
					"DiscPrcnt" : $('#totalDiscPercent-'+no).val(),
					"VatGroup" : $('#vat-code-'+no).val(),
					"VatRate" : $('#vat-rate-'+no).val(),
					"VatAmount" : $('#vat-amount-'+no).val(),
					"totalVatAmount" : $('#vat-total-'+no).val(),
					"LineTotal" : $('#line-total-'+no).val(),
					"LineSysTotal" : $('#line-sys-total-'+no).val(),
					'discDiff' : $('#disc-diff-'+no).val(),
					'sale_team' : $('#sale_team').val(),
					'whsCode' : $('#whs-'+no).val(),
					'TreeType' : $('#tree-type-'+no).val(),
					'uid' : uid,
					'father_uid' : $('#father-uid-'+no).val(),
					'LineText' : $.trim($('#text-'+uid).val())
				}

				if(uomEntry == "") {
					uomError++;
					$('#uom-'+no).addClass('has-error');
				}
				else {
					$('#uom-'+no).removeClass('has-error');
				}

				details.push(row);
				count++;
				lineNum++;
			}
		}); //--- end each function


		if(count === 0) {
			swal("ไม่พบรายการสินค้า");
			$('.btn-save').removeAttr('disabled');
			return false;
		}

		if(uomError > 0) {
			swal("กรุณาระบุ Uom");
			$('.btn-save').removeAttr('disabled');
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		if(belowCost > 0) {
			swal({
				title:'ราคาต่ำกว่าทุน !',
				text:'พบรายการที่ราคาขายต่ำกว่าต้นทุน คุณต้องการบันทึกต่อไปหรือไม่',
				type:'warning',
				showCancelButton:true,
				confirmButtonColor:'#f44336',
				confirmButtonText:'Confirm',
				cancelButtonText:'Cancel',
				closeOnConfirm:true
			}, function(isConfirm) {
				if(isConfirm) {
					setTimeout(() => {
						add(data);
					}, 200);
				}
				else {
					$('.btn-save').removeAttr('disabled');
				}
			});
		}
		else {
			add(data);
		}

	}, 500);
}


function add(data) {
	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:JSON.stringify(data),
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				if(ds.status === 'success') {
					if(ds.ex == 1) {
						swal({
							title:'Oops !',
							text: ds.message,
							type:'info'
						}, function() {
							setTimeout(function(){
								viewDetail(ds.code);
							}, 500);
						});
					}
					else {
						swal({
							title:'Success',
							type:'success',
							timer:1000
						});

						setTimeout(function(){
							viewDetail(ds.code);
						}, 1200);
					}
				}
				else {
					swal({
						title:'Error!',
						text:ds.message,
						type:'error'
					});
				}
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}

			$('.btn-save').removeAttr('disabled');
		}
	});
}


function updateAsDraft() {
	$('#is_draft').val(1);

	saveUpdate();
}




function saveUpdate() {
	$('.btn-save').attr('disabled', 'disabled');

	setTimeout(function() {

		let mustApprove = 0;
		let max_diff = 0;
		let belowCost = 0;
		let max_discount = parseDefault(parseFloat($('#max_discount').val()), 0);
		let max_amount = parseDefault(parseFloat($('#max_amount').val()), 0);

		let docTotal = parseDefault(parseFloat($('#docTotal').val()), 0);
		let sysTotal = parseDefault(parseFloat($('#sysTotalAmount').val()), 0);
		let billDisc = parseDefault(parseFloat($('#discPrcnt').val()), 0);
		let billDiscAmount = parseDefault(parseFloat($('#discAmount').val()), 0);

		$('.disc-diff').each(function() {
			let no = $(this).data('no');
			let bCost = parseDefault(parseInt($('#bCost-'+no).val()), 0);
			belowCost += bCost;

			if($(this).val() > 0) {
				let diff = parseDefault(parseFloat($(this).val()), 0);
				max_diff =  diff > max_diff ? diff : max_diff;
			}
		});

		if(billDisc > 0 && billDiscAmount > 0 && sysTotal > 0) {
			let exDisc = (billDiscAmount/sysTotal) * 100;
			max_diff += exDisc;
		}

		if(max_diff > 0) {
			if(max_diff >= max_discount) {
				mustApprove++;
			}
		}

		if(mustApprove == 0) {
			mustApprove = docTotal > max_amount ? 1 : 0;
		}

		var ds = {
			//---- Right column
			'code' : $('#code').val(),
			'isDraft' : $('#is_draft').val(),
			'SlpCode' : $('#sale_id').val(),
			'CardCode' : $.trim($('#CardCode').val()),  //****** required
			'CardName' : $('#CardName').val(),
			'CntctCode' : $('#CntctCode').val(),
			'ContactPerson' : $('#contact').val(),
			'NumAtCard' : $('#NumAtCard').val(),
			'Attn1' : $('#Attn1').val(),
			'Attn2' : $('#Attn2').val(),
			'Type' : $('#Type').val(),
			'Project' : $('#Project').val(),
			'Phone' : $('#phone').val(),
			'Payment' : $('#payment').val(),
			'OwnerCode' : $('#owner').val(),
			'ShipToCode' : $('#shipToCode').val(),
			'ShipTo' : $('#ShipTo').val(),
			//--- right Column
			'DocDate' : $('#DocDate').val(), //****** required
			'DocDueDate' : $('#ShipDate').val(), //****** required
			'TextDate' : $('#TextDate').val(), //****** required
			'PayToCode' : $('#billToCode').val(),
			'BillTo' : $('#BillTo').val(),
			//---- footer
			'comments' : $.trim($('#comments').val()),
			'discPrcnt' : billDisc,
			'disAmount' : billDiscAmount,
			'roundDif' : parseDefault(parseFloat($('#roundDif').val()), 0),
			'tax' : parseDefault(parseFloat($('#tax').val()), 0), //-- VatSum
			'docTotal' : docTotal,
			'sysTotal' : sysTotal,
			'mustApprove' : mustApprove > 0 ? 1 : 0,
			'maxDiff' : max_diff,
			'VatGroup' : $('#vat_code').val(),
			'VatRate' : $('#vat_rate').val(),
			'sale_team' : $('#sale_team').val(),
			'Address' : {
				'bAddress' : $('#billToCode').val(),
				'bAddress2' : $('#billToCode option:selected').data('name'),
				'sAddress' : $('#shipToCode').val(),
				'sAddress2' : $('#shipToCode option:selected').data('name'),
				'bStreet' : $('#Street-b').val(),
				'bStreetNo' : $('#StreetNo-b').val(),
				'bBlock' : $('#Block-b').val(),
				'bCity' : $('#City-b').val(),
				'bCounty' : $('#County-b').val(),
				'bZipCode' : $('#ZipCode-b').val(),
				'bCountry' : $('#Country-b').val(),
				'sStreet' : $('#Street-s').val(),
				'sStreetNo' : $('#StreetNo-s').val(),
				'sBlock' : $('#Block-s').val(),
				'sCity' : $('#City-s').val(),
				'sCounty' : $('#County-s').val(),
				'sZipCode' : $('#ZipCode-s').val(),
				'sCountry' : $('#Country-s').val()
			}
		}


		//--- check required parameter
		if(ds.CardCode.length === 0) {
			swal("กรุณาระบุลูกค้า");
			$('#CardCode').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#CardCode').removeClass('has-error');
		}

		if(!isDate(ds.DocDate)) {
			swal("Invalid Posting Date");
			$('#DocDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#DocDate').removeClass('has-error');
		}


		if(!isDate(ds.DocDueDate)) {
			swal("Invalid Delivery Date");
			$('#DocDueDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#DocDueDate').removeClass('has-error');
		}

		if(!isDate(ds.TextDate)) {
			swal("Invalid Document Date");
			$('#TextDate').addClass('has-error');
			$('.btn-save').removeAttr('disabled');
			return false;
		}
		else {
			$('#TextDate').removeClass('has-error');
		}


		var disc_error = 0;
		//--- check discount
		$('.disc-error').each(function() {
			no = $(this).data('id');
			if($(this).val() == 1) {
				$('#disc-error-'+no).addClass('has-error');
				disc_error++;
			}
			else {
				$('#disc-error-'+no).removeClass('has-error');
			}
		});

		if(disc_error > 0) {
			swal({
				title:'Invalid Discount',
				type:'error'
			});

			$('.btn-save').removeAttr('disabled');
			return false;
		}

		if(ds.discPrcnt < 0 || ds.discPrcnt > 100) {
			swal({
				title:"Invalid bill discount",
				type:'error'
			});

			$('.btn-save').removeAttr('disabled');
			return false;
		}


		//---- get rows details
		var count = 0;
		var details = [];
		var lineNum = 0;
		var uomError = 0;

		$('.item-code').each(function() {
			let no = $(this).data('id');

			let itemCode = $('#itemCode-'+no).val();

			if(itemCode.length) {
				//--- ถ้ามีการระบุข้อมูล
				let uomEntry = $('#uom-'+no).val();
				let uid = $('#uid-'+no).val();

				var row = {
					"LineNum" : lineNum,
					"ItemCode" : itemCode,
					"ItemName" : $('#itemName-'+no).val(),
					"Description" : $('#itemDetail-'+no).val(),
					"Price" : $('#price-'+no).val(),
					"stdPrice" : $('#stdPrice-'+no).val(),
					"SellPrice" : $('#sellPrice-'+no).val(),
					"sysSellPrice" : $('#sysSellPrice-'+no).val(),
					"Quantity" : $('#line-qty-'+no).val(),
					"UomEntry" : uomEntry,
					"sysDiscLabel" : $('#sys-disc-label-'+no).val(),
					"disc1" : $('#disc1-'+no).val(),
					"disc2" : $('#disc2-'+no).val(),
					"disc3" : $('#disc3-'+no).val(),
					"discAmount" : $('#disc-amount-'+no).val(),
					"totalDiscAmount" : $('#line-disc-amount-'+no).val(),
					"DiscPrcnt" : $('#totalDiscPercent-'+no).val(),
					"VatGroup" : $('#vat-code-'+no).val(),
					"VatRate" : $('#vat-rate-'+no).val(),
					"VatAmount" : $('#vat-amount-'+no).val(),
					"totalVatAmount" : $('#vat-total-'+no).val(),
					"LineTotal" : $('#line-total-'+no).val(),
					"LineSysTotal" : $('#line-sys-total-'+no).val(),
					'discDiff' : $('#disc-diff-'+no).val(),
					'sale_team' : $('#sale_team').val(),
					'whsCode' : $('#whs-'+no).val(),
					'TreeType' : $('#tree-type-'+no).val(),
					'uid' : uid,
					'father_uid' : $('#father-uid-'+no).val(),
					'LineText' : $.trim($('#text-'+uid).val())
				}

				if(uomEntry == "") {
					$('#uom-'+no).addClass('has-error');
					uomError++;
				}
				else {
					$('#uom-'+no).removeClass('has-error');
				}

				details.push(row);
				count++;
				lineNum++;
			}
		}); //--- end each function


		if(count === 0) {
			swal("ไม่พบรายการสินค้า");
			$('.btn-save').removeAttr('disabled');
			return false;
		}

		if(uomError > 0) {
			swal("กรุณาระบุ Uom");
			$('.btn-save').removeAttr('disabled');
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		//--- หากไม่มีข้อผิดพลาด

		if(belowCost > 0) {
			swal({
				title:'ราคาต่ำกว่าทุน !',
				text:'พบรายการที่ราคาขายต่ำกว่าต้นทุน คุณต้องการบันทึกต่อไปหรือไม่',
				type:'warning',
				showCancelButton:true,
				confirmButtonColor:'#f44336',
				confirmButtonText:'Confirm',
				cancelButtonText:'Cancel',
				closeOnConfirm:true
			}, function(isConfirm) {
				if(isConfirm) {
					setTimeout(() => {
						update(data);
					}, 200);
				}
				else {
					$('.btn-save').removeAttr('disabled');
				}
			});
		}
		else {
			update(data);
		}
	}, 500);
}


function update(data) {
	load_in();
	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:JSON.stringify(data),
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				if(ds.status === 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					setTimeout(function(){
						viewDetail(ds.code);
					}, 1200);
				}
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}

			$('.btn-save').removeAttr('disabled');
		}
	});
}



$('#CardCode').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	select:function(event, ui) {
		let code = ui.item.value;
		let name = ui.item.name;

		if(code.length && name.length) {
			$('#CardCode').val(code);
			$('#CardName').val(name);

			get_customer(code);

			//---- create Address ship to
			get_address_ship_to_code(code);

			//---- create Address bill to
			get_address_bill_to_code(code);

			$('#itemCode-1').focus();

		}
		else {
			$('#CardCode').val('');
			$('#CardName').val('');
			$('#priceList').val('');
			$('#payment').val(-1);
		}
	}
})


function get_price_list(code) {
	$.ajax({
		url:HOME + 'get_customer_price_list',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code
		},
		success:function(rs) {
			$('#priceList').val(rs);
		}
	})
}


function get_customer(code) {
	$.ajax({
		url:HOME + 'get_customer_order_data',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let phone = ds.Phone1;
				phone = (phone != null && ds.Phone2 != null) ? phone + ", "+ds.Phone2 : phone;
				$('#payment').val(ds.GroupNum);
				$('#priceList').val(ds.ListNum);
				$('#contact').val(ds.CntctPrsn);
				$('#CntctCode').val(ds.CntctCode);
				$('#phone').val(phone);
				$('#sale_id').val(ds.SlpCode).trigger('change');
				$('#sale_name').val(ds.sale_name);
			}
		}
	})
}



function getStock(no) {
	let whsCode = $('#whs-'+no).val();
	let itemCode = $('#itemCode-'+no).val();

	if(whsCode != '' && itemCode.length) {
		$.ajax({
			url:BASE_URL + 'items/get_stock',
			type:'GET',
			cache:false,
			data:{
				'ItemCode' : itemCode,
				'WhsCode' : whsCode
			},
			success:function(rs) {
				if(isJson(rs)) {
					let ds = JSON.parse(rs);
					$('#onhand-'+no).val(ds.OnHand);
					$('#commited-'+no).val(ds.Commited);
					$('#onorder-'+no).val(ds.OnOrder);
				}
				else {
					$('#onhand-'+no).val(0);
					$('#commited-'+no).val(0);
					$('#onorder-'+no).val(0);
				}
			}
		})
	}
}


function editShipTo() {
	$('#shipToModal').modal('show');
}


function updateShipTo() {
	let street = $('#s-Street').val();
	let streetNo = $('#s-StreetNo').val();
	let block = $('#s-Block').val();
	let city = $('#s-City').val();
	let county = $('#s-County').val();
	let zipCode = $('#s-ZipCode').val();
	let country = $('#s-Country').val();

	$('#Street-s').val(street);
	$('#StreetNo-s').val(streetNo);
	$('#Block-s').val(block);
	$('#City-s').val(city);
	$('#County-s').val(county);
	$('#ZipCode-s').val(zipCode);

	street = street === "" ? "" : street + " ";
	streetNo = streetNo === "" ? "" : streetNo + " ";
	block = block === "" ? "" : block + " ";
	city = city === "" ? "" : city + " ";
	county = county === "" ? "" : county + " ";
	zipCode = zipCode === "" ? "" : zipCode + " ";
	country = country === "" ? "" : country + " ";

	let adr = street + streetNo + block + city + county + zipCode + country;

	adr = $.trim(adr);

	$('#ShipTo').val(adr);

	$('#shipToModal').modal('hide');
}


function updateBillTo() {
	let street = $('#b-Street').val();
	let streetNo = $('#b-StreetNo').val();
	let block = $('#b-Block').val();
	let city = $('#b-City').val();
	let county = $('#b-County').val();
	let zipCode = $('#b-ZipCode').val();
	let country = $('#b-Country').val();

	$('#Street-b').val(street);
	$('#StreetNo-b').val(streetNo);
	$('#Block-b').val(block);
	$('#City-b').val(city);
	$('#County-b').val(county);
	$('#ZipCode-b').val(zipCode);

	street = street === "" ? "" : street + " ";
	streetNo = streetNo === "" ? "" : streetNo + " ";
	block = block === "" ? "" : block + " ";
	city = city === "" ? "" : city + " ";
	county = county === "" ? "" : county + " ";
	zipCode = zipCode === "" ? "" : zipCode + " ";
	country = country === "" ? "" : country + " ";

	let adr = street + streetNo + block + city + county + zipCode + country;

	adr = $.trim(adr);

	$('#BillTo').val(adr);
	$('#billToModal').modal('hide');
}



function get_address_ship_to_code(code)
{
	$.ajax({
		url:HOME + 'get_address_ship_to_code',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var data = $.parseJSON(rs);
				var source = $('#ship-to-template').html();
				var output = $('#shipToCode');
				render(source, data, output);

				get_address_ship_to();
			}
			else {
				$('#shipToCode').html('');
			}
		}
	});
}

function get_address_ship_to() {
	var code = $('#CardCode').val()
	var adr_code = $('#shipToCode').val();

	$.ajax({
		url:HOME + 'get_address_ship_to',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code,
			'Address' : adr_code
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				let street = ds.street === "" ? "" : ds.street + " ";
				let streetNo = ds.streetNo === "" ? "" : ds.streetNo + " ";
				let block = ds.block === "" ? "" : ds.block + " ";
				let city = ds.city === "" ? "" : ds.city + " ";
				let county = ds.county === "" ? "" : ds.county + " ";
				let zipCode = ds.zipCode === "" ? "" : ds.zipCode;

				let adr = street+streetNo+block+city+county+zipCode;

				$('#Street-s').val(ds.street);
				$('#StreetNo-s').val(ds.streetNo);
				$('#Block-s').val(ds.block);
				$('#City-s').val(ds.city);
				$('#County-s').val(ds.county);
				$('#ZipCode-s').val(ds.zipCode);

				$('#ShipTo').val(adr);
			}
		}
	})
}


function editBillTo() {
	$('#billToModal').modal('show');
}


function get_address_bill_to_code(code)
{
	$.ajax({
		url:HOME + 'get_address_bill_to_code',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var data = $.parseJSON(rs);
				var source = $('#bill-to-template').html();
				var output = $('#billToCode');
				render(source, data, output);

				get_address_bill_to();
			}
			else {
				$('#billToCode').html('');
			}
		}
	})
}


function get_address_bill_to() {
	var code = $('#CardCode').val();
	var adr_code = $('#billToCode').val();
	$.ajax({
		url:HOME + 'get_address_bill_to',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code,
			'Address' : adr_code
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				let street = ds.street === "" ? "" : ds.street + " ";
				let streetNo = ds.streetNo === "" ? "" : ds.streetNo + " ";
				let block = ds.block === "" ? "" : ds.block + " ";
				let city = ds.city === "" ? "" : ds.city + " ";
				let county = ds.county === "" ? "" : ds.county + " ";
				let zipCode = ds.zipCode === "" ? "" : ds.zipCode;

				let adr = street+streetNo+block+city+county+zipCode;

				$('#Street-b').val(ds.street);
				$('#StreetNo-b').val(ds.streetNo);
				$('#Block-b').val(ds.block);
				$('#City-b').val(ds.city);
				$('#County-b').val(ds.county);
				$('#ZipCode-b').val(ds.zipCode);
				$('#Country-b').val(ds.country);

				$('#BillTo').val(adr);
			}
		}
	})
}


function insertBefore(rowNo) {
	setTimeout(() => {
		var no = $('#row-no').val();
		var data = {"no" : no, "uid" : uniqueId()};
		var source = $('#row-template').html();
		var output = $('#row-'+rowNo);

		render_before(source, data, output);
		reIndex();
		init();
		$('#itemCode-'+no).focus();
		no++;
		$('#row-no').val(no);
		return no;
	}, 100)
}

function addRow() {
	setTimeout(() => {
		var no = $('#row-no').val();
		var data = {"no" : no, "uid" : uniqueId()};
		var source = $('#row-template').html();
		var output = $('#details-template');

		render_append(source, data, output);
		reIndex();
		init();
		$('#itemCode-'+no).focus();
		no++;
		$('#row-no').val(no);
		return no;
	}, 100)
}

function addChildRows(childs, fno) {
	var row = fno;
	childs.forEach(function(ds, index) {
		let no = $('#row-no').val();
		let price = parseDefault(parseFloat(ds.Price), 0.00);
		let sellPrice = parseDefault(parseFloat(ds.SellPrice), 0.00);
		let lineTotal = parseDefault(parseFloat(ds.LineTotal), 0.00);
		let qty = parseDefault(parseFloat(ds.Qty), 1);
		let disc1 = parseDefault(parseFloat(ds.disc1), 0.00);

		ds.no = no;
		ds.Price = price;
		ds.SellPrice = sellPrice;
		ds.LineTotal = lineTotal;
		ds.stdPriceLabel = price.toFixed(2);
		ds.priceLabel = price.toFixed(2);
		ds.sellPriceLabel = sellPrice.toFixed(2);
		ds.lineTotalLabel = lineTotal.toFixed(2);
		ds.Qty = qty;

		let source = $('#childRow-template').html();
		let output = $('#row-'+row);

		render_after(source, ds, output);
		$('#whs-'+no).val(ds.dfWhsCode);
		$('#uom-'+no).val(ds.UomEntry);
		row = no;
		no++;
		$('#row-no').val(no);
		console.log(row);
	})

	reIndex();
	init();
}


function insertTextRow(rowNo) {
	setTimeout(() => {
		var no = $('#row-no').val();
		var uid = $('#uid-'+rowNo).val();
		var data = {"no" : no, "parentNo" : rowNo, "uid" : uid};
		var source = $('#text-template').html();
		var output = $('#row-'+rowNo);

		render_after(source, data, output);
		reIndex();
		init();
		$('#text-'+uid).focus();
		no++;
		$('#row-no').val(no);
		$('#add-text-'+rowNo).addClass('hide');
		return no;
	}, 100)
}

function removeTextRow(rowNo, parentNo) {
	$('#row-'+rowNo).remove();
	$('#add-text-'+parentNo).removeClass('hide');
	reIndex();
	recalTotal();
}

function removeRow(no) {
	var type = $('#tree-type-'+no).val();
	var uid = $('#uid-'+no).val();

	if($('#text-'+uid).length) {
		textNo = $('#text-'+uid).data('no');
		$('#row-'+textNo).remove();
	}

	if(type == 'S') {
		let uid = $('#uid-'+no).val();
		if(uid.length) {
			$('.child-'+uid).each(function() {
				let rowNo = $(this).data('no');
				cuid = $('#uid-'+rowNo).val();

				if($('#text-'+cuid).length) {
					textNo = $('#text-'+cuid).data('no');
					$('#row-'+textNo).remove();
				}

				$('#row-'+rowNo).remove();
			});
		}
	}

	$('#row-'+no).remove();

	reIndex();
	recalTotal();
}


function removeRows() {
	$('.del-chk').each(function() {
		if($(this).is(':checked')) {
			var no = $(this).val();
			var type = $('#tree-type-'+no).val();

			if(type == 'S') {
				let uid = $('#uid-'+no).val();

				if(uid.length) {
					$('.child-'+uid).each(function() {
						let rowNo = $(this).data('no');
						$('#row-'+rowNo).remove();
					});
				}
			}

			$('#row-'+no).remove();
		}
	})

	reIndex();
	recalTotal();
}


function removeChildRows(no, recal) {
	let uid = $('#uid-'+no).val();

	if(uid.length) {
		$('.child-'+uid).each(function() {
			rowNo = $(this).data('no');
			$('#row-'+rowNo).remove();
		});

		reIndex();
		if(recal) {
			recalTotal();
		}
	}
}

function clearRow(no) {
	var data = {"no" : no};
	var source = $('#normal-template').html();
	var output = $('#row-'+no);
	render(source, data, output);
	$('#row-'+no).removeClass('error');
	$('#row-'+no).removeClass('dummy');
	$('#row-'+no).removeClass('father');
	init();
}


function clearRowData(no) {
	var type = $('#tree-type-'+no).val();
	var uid = $('#uid-'+no).val();

	if($('#text-'+uid).length) {
		textNo = $('#text-'+uid).data('no');
		$('#row-'+textNo).remove();
	}

	if(type == 'S') {
		let uid = $('#uid-'+no).val();

		if(uid.length) {
			$('.child-'+uid).each(function() {
				rowNo = $(this).data('no');
				cuid = $('#uid-'+rowNo).val();

				if($('#text-'+cuid).length) {
					textNo = $('#text-'+cuid).data('no');
					$('#row-'+textNo).remove();
				}

				$('#row-'+rowNo).remove();
			});
		}
	}

	clearRow(no);
	reIndex();
	recalTotal();
}



function getItemData(no) {
	isRequesting = 1;
	let itemCode = $('#itemCode-'+no).val();
	let cardCode = $('#CardCode').val();
	let priceList = $('#priceList').val();
	let docDate = $('#DocDate').val();
	let payment = $('#payment').val();

	if(cardCode == "") {
		swal({
			title:'Oops',
			text:'กรุณาระบุลูกค้า',
			type:'warning'
		}, function() {
			setTimeout(() => {
				$('#CardCode').focus();
			}, 200);
		});

		$('#itemCode-'+no).val('');
		isRequesting = 0;
		return false;
	}

	//removeChildRows(no, 0);

	setTimeout(function() {
		load_in();

		$.ajax({
			url:BASE_URL + "items/get_item_data",
			type:"GET",
			cache:false,
			data:{
				'ItemCode' : itemCode,
				'CardCode' : cardCode,
				'PriceList' : priceList,
				'DocDate' : docDate,
				'Payment' : payment
			},
			success:function(rs) {
				load_out();
				isRequesting = 0;
				var rs = $.trim(rs);
				if(isJson(rs)) {

					var ds = $.parseJSON(rs);
					var price = parseFloat(ds.Price);
					var sellPrice = parseDefault(parseFloat(ds.SellPrice), 0.00);
					var lineTotal = parseFloat(ds.LineTotal);

					$('#stdPrice-'+no).val(price); // stdPrice
					$('#stdPrice-label-'+no).val(addCommas(price.toFixed(2)));
					$('#sellPrice-'+no).val(sellPrice);
					$('#price-'+no).val(price);
					$('#cost-'+no).val(ds.Cost);
					$('#sysSellPrice-'+no).val(sellPrice);
					$('#disc-amount-'+no).val(ds.discAmount);
					$('#line-disc-amount-'+no).val(ds.totalDiscAmount);
					$('#line-total-'+no).val(lineTotal);
					$('#vat-rate-'+no).val(ds.VatRate);
					$('#vat-amount-'+no).val(ds.VatAmount);
					$('#vat-total-'+no).val(ds.TotalVatAmount);
					$('#sys-disc-label-'+no).val(ds.sysDiscLabel);
					$('#disc1-'+no).val(roundNumber(ds.disc1), 2);
					$('#disc2-'+no).val(ds.disc2);
					$('#disc3-'+no).val(ds.disc3);
					$('#itemName-'+no).val(ds.ItemName);
					$('#itemDetail-'+no).val(ds.Description);
					$('#whs-'+no).val(ds.dfWhsCode);
					$('#onhand-'+no).val(ds.OnHand);
					$('#commited-'+no).val(ds.Commited);
					$('#onorder-'+no).val(ds.OnOrder);
					$('#line-qty-'+no).val(ds.Qty);
					$('#uom-'+no).val(ds.UomEntry);
					$('#price-label-'+no).val(addCommas(price.toFixed(2)));
					$('#sysSellPrice-'+no).val(sellPrice);
					$('#disc-label-'+no).val(ds.discLabel);
					$('#vat-code-'+no).val(ds.VatGroup);
					$('#sell-price-'+no).val(sellPrice);
					$('#total-label-'+no).val(addCommas(lineTotal.toFixed(2)));
					$('#line-qty-'+no).focus();
					$('#uid-'+no).val(ds.uid);
					$('#tree-type-'+no).val(ds.TreeType);
					$('#is-blank-'+no).val(0);

					$('#add-text-'+no).removeClass('hide');

					if(ds.dummy == '1') {
						$('#uom-'+no).removeAttr('disabled');
						$('#row-'+no).addClass('dummy');
					}
					else {
						$('#uom-'+no).attr('disabled', 'disabled');
						$('#row-'+no).removeClass('dummy');
					}

					if(ds.childs.length) {
						$('#row-'+no).addClass('father');
						$('#price-label-'+no).attr('disabled', 'disabled');
						$('#disc1-'+no).attr('disabled', 'disabled');
						$('#disc2-'+no).attr('disabled', 'disabled');
						$('#disc3-'+no).attr('disabled', 'disabled');
						addChildRows(ds.childs, no);
					}
					else {
						$('#row-'+no).removeClass('father');
						$('#price-label-'+no).removeAttr('disabled');
						$('#disc1-'+no).removeAttr('disabled');
						$('#disc2-'+no).removeAttr('disabled');
						$('#disc3-'+no).removeAttr('disabled');
					}

					recalAmount(no);
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					})
				}
			}
		})
	}, 200);
}




function parse_discount(d1, d2, d3, price) {
	discLabel = {
		"disc1" : d1,
		"disc2" : d2,
		"disc3" : d3,
		"sellPrice" : price,
		"discountAmount" : 0
	}

	if(d1 > 0 && price != 0) {
		let disc_1 = parseDefault(parseFloat(d1), 0);
		if(disc_1 > 0) {
			amount = (disc_1 * 0.01) * price;
			price -= amount;
			discLabel['discountAmount'] += amount;
			discLabel['sellPrice'] = price;

			if(d2 > 0) {
				let disc_2 = parseDefault(parseFloat(d2), 0);
				if(disc_2 > 0) {
					amount = (disc_2 * 0.01) * price;
					price -= amount;
					discLabel['discountAmount'] += amount;
					discLabel['sellPrice'] = price;

					if(d3 > 0) {
						let disc_3 = parseDefault(parseFloat(d3), 0);
						if(disc_3 > 0) {
							amount = (disc_3 * 0.01) * price;
							price -= amount;
							discLabel['discountAmount'] += amount;
							discLabel['sellPrice'] = price;
						}
					}
				}
			}
		}
	}

	return discLabel;
}


function recalDiscount(no) {
	let d1 = $('#disc1-'+no).val();
	let d2 = $('#disc2-'+no).val();
	let d3 = $('#disc3-'+no).val();
	let err = 0;

	if(d1 > 100 || (d1 == 0 && d2 > 0) || (d1 == 0 && d3 > 0)) {
		$('#disc1-'+no).addClass('has-error');
		err++;
	}
	else {
		$('#disc1-'+no).removeClass('has-error');
	}

	if(d2 > 100 || (d2 == 0 && d3 > 0)) {
		$('#disc2-'+no).addClass('has-error');
		err++;
	}
	else {
		$('#disc2-'+no).removeClass('has-error');
	}

	if(d3 > 100) {
		$('#disc3-'+no).addClass('has-error');
		err++;
	}
	else {
		$('#disc3-'+no).removeClass('has-error');
	}

	if(err > 0) {
		$('#disc-error-'+no).val(1);
		return false;
	}
	else {
		$('#disc-error-'+no).val(0);
	}

	recalAmount(no);
}



function recalAmount(no) {
	let type = $('#tree-type-'+no).val();

	setTimeout(function() {
		let disc1 = $('#disc1-'+no).val();
		let disc2 = $('#disc2-'+no).val();
		let disc3 = $('#disc3-'+no).val();
		let sysSellPrice = parseDefault(parseFloat($('#sysSellPrice-'+no).val()), 0.00);
		let priceLabel = removeCommas($('#price-label-'+no).val());
		let price = roundNumber(parseDefault(parseFloat(priceLabel), 0.00));
		if(price < 0) {
			price = 0.00;
			$('#price-label-'+no).val(0.00);
		}

		let cost = roundNumber(parseDefault(parseFloat($('#cost-'+no).val()), 0.00));

		$('#price-'+no).val(price);
		$('#price-label-'+no).val(addCommas(price));

		qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);

		disc = parse_discount(disc1, disc2, disc3, price);

		discountAmount = disc.discountAmount;
		sellPrice = roundNumber(disc.sellPrice, 2); // 4 digit
		discPrcnt = discountAmount > 0 ? (discountAmount / price) * 100 : 0.00;
		discPrcnt = roundNumber(discPrcnt);

		$('#totalDiscPercent-'+no).val(discPrcnt.toFixed(2));

		if(sellPrice < 0 || sellPrice > price) {
			$('.disc-' + no).addClass('has-error');
			$('#disc-error-'+no).val(1);
			return false;
		}
		else {

			vat_rate = parseDefault(parseFloat($('#vat-rate-'+no).val()), 0) * 0.01;

			vatAmount = roundNumber(sellPrice * vat_rate);

			vatTotal = roundNumber(qty * vatAmount, 2); // 4 digit

			lineAmount = roundNumber(qty * sellPrice, 2); // 2 digit

			lineDiscAmount = roundNumber(qty * discountAmount, 2); // 4 digit

			if( sysSellPrice > sellPrice ) {

				diff = roundNumber(sysSellPrice - sellPrice, 2); // 4 digit

				percentDiff = (diff/sysSellPrice) * 100;
				percentDiff = roundNumber(percentDiff);

				$('#disc-diff-'+no).val(percentDiff);
			}
			else {
				$('#disc-diff-'+no).val(0);
			}

			$('#disc-error-'+no).val(0);
			$('.disc-' + no).removeClass('error');
			$('#disc-amount-'+no).val(discountAmount.toFixed(2));
			$('#line-disc-amount-'+no).val(lineDiscAmount);
			$('#sellPrice-'+no).val(sellPrice);
			$('#sell-price-'+no).val(addCommas(sellPrice));
			$('#vat-amount-'+no).val(vatAmount);
			$('#vat-total-'+no).val(vatTotal);
			$('#line-total-'+no).val(lineAmount);
			$('#line-sys-total-'+no).val(sysSellPrice * qty);
			$('#total-label-'+no).val(addCommas(lineAmount));

			if(sellPrice < cost) {
				$('#row-'+no).addClass('error');
				$('#bCost-'+no).val(1);
			}
			else {
				$('#row-'+no).removeClass('error');
				$('#bCost-'+no).val(0);
			}

			if(type == 'S') {
				recalChilds(no);
			}

			recalTotal();
		}
	}, 200)

}


function recalChilds(no) {
	let uid = $('#uid-'+no).val();
	let qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
	$('.'+uid).each(function() {
		let rowNo = $(this).data('id');
		let baseQty = parseDefault(parseFloat($(this).data('qty')), 1);
		let newQty = qty * baseQty;
		$(this).val(newQty);
		recalAmount(rowNo);
	});
}


function getDiscDiff(old_price, new_price) {
	let diff = old_price - new_price;

	if(diff > 0) {
		return diff/old_price * 0.01;
	}

	return 0;
}



function recalTotal() {
	var total = 0.00; //--- total amount after row discount
	var totalTaxAmount = 0.00;
	var sysTotal = 0.00;
	var df_rate = parseDefault(parseFloat($('#vat_rate').val()), 7); //---- 7%
	var taxRate = df_rate * 0.01;
	var rounding = 0;

	$('.line-num').each(function(){
		let no = $(this).val();
		let qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);

		let price = roundNumber(parseDefault(parseFloat($('#sellPrice-'+no).val()), 0.00));
		let amount = roundNumber(parseDefault(parseFloat($('#line-total-'+no).val()), 0.00));
		let rate = parseDefault(parseFloat($('#vat-rate-'+no).val()), 0.00);
		let line_sys_amount = roundNumber(parseDefault(parseFloat($('#line-sys-total-'+no).val()), 0.00));

		sysTotal += line_sys_amount;

		if(qty > 0 && price > 0)
		{
			total += amount;

			if(rate > 0) {
				totalTaxAmount += amount;
			}
		}
	});

	//--- update bill discount
	var disc = roundNumber(parseDefault(parseFloat($('#discPrcnt').val()), 0));
	var billDiscAmount = roundNumber(parseFloat(total * (disc * 0.01)));
	$('#discAmount').val(billDiscAmount);
	$('#discAmountLabel').val(addCommas(billDiscAmount));

	//---- bill discount amount
	amountAfterDisc = roundNumber(parseDefault(parseFloat(total - billDiscAmount), 0.00)); //--- มูลค่าสินค้า หลังหักส่วนลด
	amountBeforeDiscWithTax = roundNumber(parseDefault(parseFloat(totalTaxAmount), 0.00)); //-- มูลค่าสินค้า เฉพาะที่มีภาษี
	//--- คำนวนภาษี หากมีส่วนลดท้ายบิล
	//--- เฉลี่ยส่วนลดออกให้ทุกรายการ โดยเอาส่วนลดท้ายบิล(จำนวนเงิน)/มูลค่าสินค้าก่อนส่วนลด
	//--- ได้มูลค่าส่วนลดท้ายบิลที่เฉลี่ยนแล้ว ต่อ บาท เช่น หารกันมาแล้ว ได้ 0.16 หมายถึงทุกๆ 1 บาท จะลดราคา 0.16 บาท
	everageBillDisc = roundNumber(parseFloat((total > 0 ? billDiscAmount/total : 0)));

	//--- นำผลลัพธ์ข้างบนมาคูณ กับ มูลค่าที่ต้องคิดภาษี (ตัวที่ไม่มีภาษีไม่เอามาคำนวณ)
	//--- จะได้มูลค่าส่วนลดที่ต้องไปลบออกจากมูลค่าสินค้าที่ต้องคิดภาษี
	totalDiscTax = roundNumber(amountBeforeDiscWithTax * everageBillDisc);

	amountToPayTax = roundNumber(amountBeforeDiscWithTax - totalDiscTax);

	taxAmount = roundNumber(amountToPayTax * taxRate);

	docTotal = amountAfterDisc + taxAmount + rounding;

	$('#sysTotalAmount').val(sysTotal);
	$('#totalAmount').val(total);
	$('#totalAmountLabel').val(addCommas(total.toFixed(2)));
	$('#tax').val(taxAmount);
	$('#taxLabel').val(addCommas(taxAmount.toFixed(2)));
	$('#docTotal').val(docTotal);
	$('#docTotalLabel').val(addCommas(docTotal.toFixed(2)));
}



$('#discAmountLabel').focusout(function(){
	var total = parseDefault(parseFloat($('#totalAmount').val()), 0);
	var disc = parseDefault(parseFloat(removeCommas($(this).val())), 0);

	if(disc < 0 ) {
		disc = 0;
		$(this).val(0);
		$('#discAmount').val(0);
	}
	else if(disc > total) {
		disc = total;
		$(this).val(addCommas(total));
		$('#discAmount').val(total);
	}
	//--- convert amount to percent
	var discPrcnt = roundNumber(total > 0 ? (disc / total) * 100 : 0);

	$('#discPrcnt').val(discPrcnt.toFixed(2));

	recalTotal();
})



$('#discPrcnt').change(function() {
	var total = parseDefault(parseFloat($('#totalAmount').val()), 0);
	var disc = $(this).val();

	if(disc < 0) {
		$(this).val(0);
	}
	else if(disc > 100) {
		$(this).addClass('has-error');
	}
	else {
		$(this).removeClass('has-error');
		let discAmount = (total * (disc * 0.01));
		$('#discAmount').val(discAmount);
		$('#discAmountLabel').val(addCommas(discAmount.toFixed(2)));

		recalTotal();
	}
});


$('#discPrcnt').focus(function() {
	$(this).select();
})



$('#roundDif').keyup(function(){
	recalTotal();
})





function init() {
	$('.item-code').autocomplete({
		source:BASE_URL + 'auto_complete/get_item_code_and_name',
		autoFocus:true,
		open:function(event){
			var $ul = $(this).autocomplete('widget');
			$ul.css('width', 'auto');
		},
		select:function(event, ui) {
			let no = $(this).data('id');
			let code = ui.item.code;
			let name = ui.item.name;

			if(code.length && name.length) {
				setTimeout(() => {
					$('#itemCode-'+no).val(code);
					$('#itemName-'+no).val(name);
					$('#itemName-'+no).focus();
					//$(this).trigger('change');
					//getItemData(no);
				},1);
			}
			else {
				$(this).val('');
			}
		}
	});


	$('.item-code').change(function() {
		let no = $(this).data('id');
		let code = $(this).val();
		if(code.length && isRequesting == 0) {
			getItemData(no);
		}
	});

	$('.item-code').focusout(function() {
		let no = $(this).data('id');
		let isBlank = $('#is-blank-'+no).val();
		let code = $(this).val();
		if(isBlank == 0 && code.length == 0 && isRequesting == 0) {
			clearRowData(no);
		}
	});

	$('.line-qty').change(function() {
		let no = $(this).data('id');
		recalAmount(no);
		setTimeout(function() {
			$('#price-label-'+no).focus();
		}, 200);
	});


	$('.line-qty').keyup(function(e) {
		if(e.keyCode == 13) {
			let no = $(this).data('id');
			setTimeout(function() {
				$('#price-label-'+no).focus();
			}, 200);
		}
	})


	$('.price').change(function() {
		let no = $(this).data('id');
		recalAmount(no);
		setTimeout(function() {
			$('#disc-label-'+no).focus();
		}, 200)
	});


	$('.price').keyup(function(e) {
		if(e.keyCode == 13) {
			let no = $(this).data('id');
			setTimeout(function() {
				$('#disc-label-'+no).focus();
			}, 200)
		}
	})


	$('.line-qty').focus(function() {
		$(this).select();
	});


	$('.price').focus(function() {
		$(this).select();
	});

	$('.disc').focus(function() {
		$(this).select();
	});

	$('.autosize').autosize();
	//$('.autosize').autosize({append: "\n"});

} //--- end init




$('#discAmount').keyup(function(e) {
	if(e.keyCode === 13) {
		$('#roundDif').focus();
	}
})


$(document).ready(function(){
	init();
})


function duplicateSQ(code) {
	swal({
    title:'Duplicate',
    text:'ต้องการสร้างใบเสนอราคาใหม่ เหมือนใบเสนอราคานี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
		confirmButtonText:'Yes',
    cancelButtonText:'No',
		closeOnConfirm:true
  },
  function(){
		load_in();
		$.ajax({
			url:HOME + 'duplicate_quotation',
			type:'POST',
			cache:false,
			data:{
				'code' : code
			},
			success:function(rs) {
				load_out();
				var rs = $.trim(rs);
				if(isJson(rs)) {
					var ds = $.parseJSON(rs);
					if(ds.status === 'success') {
						setTimeout(() => {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});

							setTimeout(() => {
								goEdit(ds.code);
							},1200);
						}, 500);

					}
					else {
						swal({
							title:"Error!",
							text:ds.message,
							type:'error'
						});
					}
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					})
				}
			}
		})
  });
}

function toggleText(el) {
	var no = el.data('id');
	var data = {"no" : no};
	var output = $('#row-'+no);

	if(el.val() == 1) {
		var source = $('#text-template').html();
	}
	else {
		var source = $('#normal-template').html();
	}

	render(source, data, output);

	init();
}


function dumpJson(code) {
	$.ajax({
		url:HOME + 'getJSON',
		type:'GET',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			console.log(rs);
		}
	})
}
