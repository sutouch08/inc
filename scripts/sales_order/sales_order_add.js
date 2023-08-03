var btnClick = 0;

function saveAsDraft(option) {
	$('#is_draft').val(1);

	validateFreeItem(option);
}



function getFreeItemRule()
{
	removeFreeRow();

	ds = {
		'DocDate' : $('#DocDate').val(),
		'CardCode' : $.trim($('#CardCode').val()),
		'Payment' : $('#payment').val(),
		'Channels' : $('#channels').val()
	};


	var items = {};
	//--- get sum item qty, amount
	$('.item-code').each(function() {
		itemCode = $(this).val();
		if(itemCode.length) {
			no = $(this).data('id');
			is_free = $('#is-free-'+no).val();
			if(is_free == 0) {
				product_id = $('#product-id-'+no).val();
				qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
				amount = parseDefault(parseFloat($('#line-total-'+no).val()), 0.00);

				if(items.hasOwnProperty(product_id)) {
					qty += parseInt(items[product_id].qty);
					amount += parseFloat(items[product_id].amount);
				}

				items[product_id] = {"itemCode" : itemCode, "qty" : qty, "amount" : amount};
			}
		}
	});

	ds.items = items;

	if(Object.keys(items).length) {
		load_in();
		$.ajax({
			url:HOME + 'get_free_item_rule',
			type:'POST',
			cache:false,
			data:{
				"json" : JSON.stringify(ds)
			},
			success:function(rs) {
				load_out();

				if(isJson(rs)) {
					ds = $.parseJSON(rs);
					$.each(ds, function(index, value) {
						if($('#free-'+value.rule_id).length) {
							$('#free-'+value.rule_id).val(value.freeQty);
						}
						else {
							source = $('#free-input-template').html();
							output = $('#free-temp');
							render_append(source, value, output);

							source = $('#free-btn-template').html();
							output = $('#free-box');
							render_append(source, value, output);
						}
					});
				}
			}
		})
	}

	$('#btn-save').removeClass('hide');
	$('#btn-draft').removeClass('hide');
}


function validateFreeItem(option) {
	console.log(btnClick);
	if(btnClick == 0) {
		btnClick = 1;
		console.log(btnClick);
		let isDraft = $('#is_draft').val();

		if(isDraft == 1) {

			if(option == 'add') {
				saveAdd();
			}
			else {
				saveUpdate();
			}
		}
		else {

			let fRemain = 0;

			$('.free-item').each(function() {
				if($(this).val() > 0) {
					fqty = parseDefault(parseInt($(this).val()), 0);
					picked = parseDefault(parseInt($(this).data('picked')), 0);

					if(fqty > 0 && picked < fqty) {
						fRemain += fqty - picked;
					}
				}
			});

			if(fRemain > 0) {
				title = 'พบรายการที่ได้รับของแถม แต่ยังไม่ได้เลือกของแถม เมื่อคุณบันทึกออเดอร์แล้ว คุณอาจไม่สามารถกลับมาเลือกของแถมภายหลังได้อีก ต้องการบันทึกออเดอร์หรือไม่ ?';
				swal({
					title:'Warning!',
					text:title,
					type:'warning',
					showCancelButton:true,
					cancelButtonText:'กลับไปแก้ไข',
					confirmButtonText:'บันทึกออเดอร์',
					closeOnConfirm:true
				},
				function(isConfirm){
					if(isConfirm) {
						if(option == 'add') {
							saveAdd();
						}
						else {
							saveUpdate();
						}
					}
					else {
						btnClick = 0;
					}
				});
			}
			else {
				if(option == 'add') {
					saveAdd();
				}
				else {
					saveUpdate();
				}
			}
		}
	}

}


function saveAdd() {
	$('#btn-save').attr('disabled', 'disabled');
	$('#btn-draft').attr('disabled', 'disabled');

	setTimeout(function() {

		let mustApprove = 0;
		let max_diff = 0;
		let emptyQuota = 0;
		let is_draft = $('#is_draft').val() == '1' ? 1 : 0;
		let creditLimit = $('#creditLimit').val() == '1' ? 1 : 0;
		let payment = $('#payment').val();

		$('.disc-diff').each(function() {
			if($(this).val() > 0) {
				mustApprove++;
				max_diff = $(this).val() > max_diff ? $(this).val() : max_diff;
			}
		});

		var ds = {
			//---- Right column
			'isDraft' : is_draft,
			'SlpCode' : $('#sale_id').val(),
			'CardCode' : $.trim($('#CardCode').val()),  //****** required
			'CardName' : $('#CardName').val(),
			'Payment' : $('#payment').val(),
			'Channels' : $('#channels').val(),
			'dimCode1' : $('#dimCode1').val(),
			'dimCode2' : $('#dimCode2').val(),
			'dimCode3' : $('#dimCode3').val(),
			'dimCode4' : $('#dimCode4').val(),
			'dimCode5' : $('#dimCode5').val(),
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
			'discPrcnt' : parseDefault(parseFloat($('#discPrcnt').val()), 0),
			'disAmount' : parseDefault(parseFloat($('#discAmount').val()), 0),
			'roundDif' : 0,
			'tax' : parseDefault(parseFloat($('#tax').val()), 0), //-- VatSum
			'docTotal' : parseDefault(parseFloat($('#docTotal').val()), 0),
			'mustApprove' : mustApprove > 0 ? 1 : 0,
			'maxDiff' : max_diff,
			'VatGroup' : $('#vat_code').val(),
			'VatRate' : $('#vat_rate').val(),
			'sale_team' : $('#sale_team').val(),
			'user_id' : $('#user_id').val(),
			'uname' : $('#uname').val()
		}

		//--- check required parameter
		if(ds.CardCode.length === 0) {
			swal("กรุณาระบุลูกค้า");
			$('#CardCode').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#CardCode').removeClass('has-error');
		}

		if(!isDate(ds.DocDate)) {
			swal("Invalid Posting Date");
			$('#DocDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#DocDate').removeClass('has-error');
		}


		if(!isDate(ds.DocDueDate)) {
			swal("Invalid Delivery Date");
			$('#DocDueDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#DocDueDate').removeClass('has-error');
		}

		if(!isDate(ds.TextDate)) {
			swal("Invalid Document Date");
			$('#TextDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#TextDate').removeClass('has-error');
		}

		let dimCount = 0;

		if(ds.dimCode1 != '') {
			dimCount++;
		}

		if(ds.dimCode2 != '') {
			dimCount++;
		}

		if(ds.dimCode3 != '') {
			dimCount++;
		}

		if(ds.dimCode4 != '') {
			dimCount++;
		}

		if(ds.dimCode5 != '') {
			dimCount++;
		}

		if(dimCount == 0) {
			swal("กรุณาเลือกหน่วยงาน");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(dimCount > 1) {
			swal("กรุณาเลือกเพียง 1 หน่วยงานเท่านั้น");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
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

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(ds.discPrcnt < 0 || ds.discPrcnt > 100) {
			swal({
				title:"Invalid bill discount",
				type:'error'
			});

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}


		//---- get rows details
		var count = 0;
		var details = [];
		var lineNum = 0;

		$('.item-code').each(function() {
			let no = $(this).data('id');
			let itemCode = $(this).val();
			if(itemCode.length > 0) {

				let quotaNo = $('#quota-'+no).val();

				if(quotaNo == "") {
					emptyQuota++;
				}

				//--- ถ้ามีการระบุข้อมูล
				var row = {
					"LineNum" : lineNum,
					"ItemCode" : itemCode,
					"Description" : $('#itemName-'+no).val(),
					"StdPrice" : $('#stdPrice-'+no).val(),
					"Price" : $('#price-'+no).val(),
					"SellPrice" : $('#sellPrice-'+no).val(),
					"sysSellPrice" : $('#sysSellPrice-'+no).val(),
					"Quantity" : $('#line-qty-'+no).val(),
					"UomCode" : $('#uom-code-'+no).val(),
					"discLabel" : $('#disc-label-'+no).val(),
					"sysDiscLabel" : $('#sys-disc-label-'+no).val(),
					"discAmount" : $('#disc-amount-'+no).val(),
					"totalDiscAmount" : $('#line-disc-amount-'+no).val(),
					"DiscPrcnt" : $('#totalDiscPercent-'+no).val(),
					"VatGroup" : $('#vat-code-'+no).val(),
					"VatRate" : $('#vat-rate-'+no).val(),
					"VatAmount" : $('#vat-amount-'+no).val(),
					"totalVatAmount" : $('#vat-total-'+no).val(),
					"LineTotal" : $('#line-total-'+no).val(),
					"policy_id" : $('#policy-id-'+no).val(),
					"rule_id" : $('#rule-id-'+no).val(),
					'discDiff' : $('#disc-diff-'+no).val(),
					'uid' : $('#free-item-'+no).data('uid'),
					'parent_uid' : $('#free-item-'+no).data('parent'),
					'picked' : $('#free-item-'+no).data('picked'),
					'is_free' : $('#is-free-'+no).val(),
					'discType' : $('#disc-type-'+no).val(),
					'WhsCode' : $('#whs-'+no).val(),
					'QuotaNo' : $('#quota-'+no).val(),
					'sale_team' : $('#sale_team').val(),
					'count_stock' : $('#count-stock-'+no).val(),
					'allow_change_discount' : $('#allow-change-discount-'+no).val()
				}

				details.push(row);
				count++;
				lineNum++;
			}
		}); //--- end each function


		if(count === 0) {
			swal("ไม่พบรายการสินค้า");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(emptyQuota > 0) {
			swal("กรุณาระบุ Quota No ให้ครบ");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		//--- หากไม่มีข้อผิดพลาด
		if(is_draft == 0 && creditLimit == 1 && payment != '-1') {

			load_in();

			const cardCode = $('#CardCode').val();
			const docTotal = parseDefault(parseFloat($('#docTotal').val()), 0);
			const orderCode = null;

			$.ajax({
				url:HOME + 'get_credit_balance',
				type:'GET',
				cache:false,
				data:{
					'CardCode' : cardCode,
					'orderCode' : orderCode
				},
				success:function(cs) {
					cs = $.trim(cs);

					if(isJson(cs)) {
						load_out();

						bs = $.parseJSON(cs);
						balance = bs.balance;

						if(balance < docTotal) {
							difamount = docTotal - balance;
							setTimeout(function() {
								swal({
									title:'เครดิตคงเหลือไม่เพียงพอ',
									text:'ไม่สามารถบันทึก SO ได้เนื่องจากเครดิตคงเหลือไม่เพียงพอ <br/> คงเหลือ **'+addCommas(balance.toFixed(2))+'**',
									type:'warning',
									html:true
								});
							}, 200);

							$('#btn-save').removeAttr('disabled');
							$('#btn-draft').removeAttr('disabled');
							btnClick = 0;
							return false;
						}
						else {

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

									$('#btn-save').removeAttr('disabled');
									$('#btn-draft').removeAttr('disabled');
								}
							});
						}
					}
					else {
						load_out();
						$('#btn-save').removeAttr('disabled');
						$('#btn-draft').removeAttr('disabled');
						btnClick = 0;
						setTimeout(function() {
							swal({
								title:'Error!',
								text:cs,
								type:'error'
							});
						}, 200);
					}
				}
			});
		}
		else {

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

					$('#btn-save').removeAttr('disabled');
					$('#btn-draft').removeAttr('disabled');
					btnClick = 0;
				}
			});
		}
	}, 500);
}



function updateAsDraft() {
	$('#is_draft').val(1);

	validateFreeItem('update');
}




function saveUpdate() {
	$('#btn-save').attr('disabled', 'disabled');
	$('#btn-draft').attr('disabled', 'disabled');

	setTimeout(function() {

		let mustApprove = 0;
		let max_diff = 0;
		let emptyQuota = 0;
		let is_draft = $('#is_draft').val() == '1' ? 1 : 0;
		let creditLimit = $('#creditLimit').val() == '1' ? 1 : 0;
		let payment = $('#payment').val();

		$('.disc-diff').each(function() {
			if($(this).val() > 0) {
				mustApprove++;
				max_diff = $(this).val() > max_diff ? $(this).val() : max_diff;
			}
		});

		var ds = {
			//---- Right column
			'isDraft' : is_draft,
			'code' : $('#code').val(),
			'SlpCode' : $('#sale_id').val(),
			'CardCode' : $.trim($('#CardCode').val()),  //****** required
			'CardName' : $('#CardName').val(),
			'Payment' : $('#payment').val(),
			'Channels' : $('#channels').val(),
			'dimCode1' : $('#dimCode1').val(),
			'dimCode2' : $('#dimCode2').val(),
			'dimCode3' : $('#dimCode3').val(),
			'dimCode4' : $('#dimCode4').val(),
			'dimCode5' : $('#dimCode5').val(),
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
			'discPrcnt' : parseDefault(parseFloat($('#discPrcnt').val()), 0),
			'disAmount' : parseDefault(parseFloat($('#discAmount').val()), 0),
			'roundDif' : 0,
			'tax' : parseDefault(parseFloat($('#tax').val()), 0), //-- VatSum
			'docTotal' : parseDefault(parseFloat($('#docTotal').val()), 0),
			'mustApprove' : mustApprove > 0 ? 1 : 0,
			'maxDiff' : max_diff,
			'VatGroup' : $('#vat_code').val(),
			'VatRate' : $('#vat_rate').val(),
			'sale_team' : $('#sale_team').val(),
			'user_id' : $('#user_id').val(),
			'uname' : $('#uname').val()
		}

		//--- check required parameter
		if(ds.CardCode.length === 0) {
			swal("กรุณาระบุลูกค้า");
			$('#CardCode').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#CardCode').removeClass('has-error');
		}

		if(!isDate(ds.DocDate)) {
			swal("Invalid Posting Date");
			$('#DocDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#DocDate').removeClass('has-error');
		}


		if(!isDate(ds.DocDueDate)) {
			swal("Invalid Delivery Date");
			$('#DocDueDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#DocDueDate').removeClass('has-error');
		}

		if(!isDate(ds.TextDate)) {
			swal("Invalid Document Date");
			$('#TextDate').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#TextDate').removeClass('has-error');
		}

		if(ds.OwnerCode == '') {
			swal("Please Select Owner");
			$('#owner').addClass('has-error');

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}
		else {
			$('#owner').removeClass('has-error');
		}

		let dimCount = 0;

		if(ds.dimCode1 != '') {
			dimCount++;
		}

		if(ds.dimCode2 != '') {
			dimCount++;
		}

		if(ds.dimCode3 != '') {
			dimCount++;
		}

		if(ds.dimCode4 != '') {
			dimCount++;
		}

		if(ds.dimCode5 != '') {
			dimCount++;
		}

		if(dimCount == 0) {
			swal("กรุณาเลือกหน่วยงาน");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(dimCount > 1) {
			swal("กรุณาเลือกเพียง 1 หน่วยงานเท่านั้น");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
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

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(ds.discPrcnt < 0 || ds.discPrcnt > 100) {
			swal({
				title:"Invalid bill discount",
				type:'error'
			});

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;

			return false;
		}


		//---- get rows details
		var count = 0;
		var details = [];
		var lineNum = 0;

		$('.item-code').each(function() {
			let no = $(this).data('id');
			let itemCode = $(this).val();
			if(itemCode.length > 0) {
				let quotaNo = $('#quota-'+no).val();

				if(quotaNo == "") {
					emptyQuota++;
				}
				//--- ถ้ามีการระบุข้อมูล
				var row = {
					"LineNum" : lineNum,
					"ItemCode" : itemCode,
					"Description" : $('#itemName-'+no).val(),
					"StdPrice" : $('#stdPrice-'+no).val(),
					"Price" : $('#price-'+no).val(),
					"SellPrice" : $('#sellPrice-'+no).val(),
					"sysSellPrice" : $('#sysSellPrice-'+no).val(),
					"Quantity" : $('#line-qty-'+no).val(),
					"UomCode" : $('#uom-code-'+no).val(),
					"discLabel" : $('#disc-label-'+no).val(),
					"sysDiscLabel" : $('#sys-disc-label-'+no).val(),
					"discAmount" : $('#disc-amount-'+no).val(),
					"totalDiscAmount" : $('#line-disc-amount-'+no).val(),
					"DiscPrcnt" : $('#totalDiscPercent-'+no).val(),
					"VatGroup" : $('#vat-code-'+no).val(),
					"VatRate" : $('#vat-rate-'+no).val(),
					"VatAmount" : $('#vat-amount-'+no).val(),
					"totalVatAmount" : $('#vat-total-'+no).val(),
					"LineTotal" : $('#line-total-'+no).val(),
					"policy_id" : $('#policy-id-'+no).val(),
					"rule_id" : $('#rule-id-'+no).val(),
					'discDiff' : $('#disc-diff-'+no).val(),
					'uid' : $('#free-item-'+no).data('uid'),
					'parent_uid' : $('#free-item-'+no).data('parent'),
					'picked' : $('#free-item-'+no).data('picked'),
					'is_free' : $('#is-free-'+no).val(),
					'discType' : $('#disc-type-'+no).val(),
					'WhsCode' : $('#whs-'+no).val(),
					'QuotaNo' : $('#quota-'+no).val(),
					'sale_team' : $('#sale_team').val(),
					'count_stock' : $('#count-stock-'+no).val(),
					'allow_change_discount' : $('#allow-change-discount-'+no).val()
				}

				details.push(row);
				count++;
				lineNum++;
			}
		}); //--- end each function


		if(count === 0) {
			swal("ไม่พบรายการสินค้า");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		if(emptyQuota > 0) {
			swal("กรุณาระบุ Quota No ให้ครบ");

			$('#btn-save').removeAttr('disabled');
			$('#btn-draft').removeAttr('disabled');
			btnClick = 0;
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		//--- หากไม่มีข้อผิดพลาด
		if(is_draft == 0 && creditLimit == 1 && payment != '-1') {

			load_in();

			const cardCode = $('#CardCode').val();
			const docTotal = parseDefault(parseFloat($('#docTotal').val()), 0);
			const orderCode = $('#code').val();

			$.ajax({
				url:HOME + 'get_credit_balance',
				type:'GET',
				cache:false,
				data:{
					'CardCode' : cardCode,
					'orderCode' : orderCode
				},
				success:function(cs) {
					cs = $.trim(cs);

					if(isJson(cs)) {
						load_out();

						bs = $.parseJSON(cs);
						balance = bs.balance;

						if(balance < docTotal) {
							difamount = docTotal - balance;
							setTimeout(function() {
								swal({
									title:'เครดิตคงเหลือไม่เพียงพอ',
									text:'ไม่สามารถบันทึก SO ได้เนื่องจากเครดิตคงเหลือไม่เพียงพอ <br/> คงเหลือ **'+addCommas(balance.toFixed(2))+'**',
									type:'warning',
									html:true
								});
							}, 200);

							$('#btn-save').removeAttr('disabled');
							$('#btn-draft').removeAttr('disabled');
							btnClick = 0;

							return false;
						}
						else {

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

									$('#btn-save').removeAttr('disabled');
									$('#btn-draft').removeAttr('disabled');
									btnClick = 0;
								}
							});
						}
					}
					else {
						load_out();

						setTimeout(function() {
							swal({
								title:'Error!',
								text:cs,
								type:'error'
							});
						}, 200);
					}
				}
			});
		}
		else {

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

					$('#btn-save').removeAttr('disabled');
					$('#btn-draft').removeAttr('disabled');
					btnClick = 0;
				}
			});
		}
	}, 500)
}


function updateShipTo() {
	var ds = {
		'address' : $('#s_address').val(),
		'block' : $('#sBlock').val(),
		'street' : $('#sStreet').val(),
		'subDistrict' : $('#sSubDistrict').val(),
		'district' : $('#sDistrict').val(),
		'province' : $('#sProvince').val(),
		'country' : $('#sCountry').val(),
		'countryName' : $('#sCountry option:selected').text(),
		'postcode' : $('#sPostCode').val()
	};

	var shipTo = "";
	shipTo += (ds.block == "" ? "" : ds.block + " ");
	shipTo += (ds.street == "" ? "" : ds.street+" ");
	shipTo += (ds.subDistrict == "" ? "" : ds.subDistrict+" ");
	shipTo += (ds.district == "" ? "" : ds.district+" ");
	shipTo += (ds.province == "" ? "" : ds.province+" ");
	shipTo += (ds.postcode == "" ? "" : ds.postcode + " ");

	if(ds.country !== "TH") {
		shipTo += ds.countryName;
	}


	$('#ShipTo').val(shipTo);
	$('#shipToModal').modal('hide');
}



function updateBillTo() {
	var ds = {
		'address' : $('#b_address').val(),
		'block' : $('#bBlock').val(),
		'street' : $('#bStreet').val(),
		'subDistrict' : $('#bSubDistrict').val(),
		'district' : $('#bDistrict').val(),
		'province' : $('#bProvince').val(),
		'country' : $('#bCountry').val(),
		'countryName' : $('#bCountry option:selected').text(),
		'postcode' : $('#bPostCode').val()
	};

	var billTo = "";
	billTo += (ds.block == "" ? "" : ds.block + " ");
	billTo += (ds.street == "" ? "" : ds.street+" ");
	billTo += (ds.subDistrict == "" ? "" : ds.subDistrict + " ");
	billTo += (ds.district == "" ? "" : ds.district + " ");
	billTo += (ds.province == "" ? "" : ds.province + " ");
	billTo += (ds.postcode == "" ? "" : ds.postcode + " ");

	if(ds.country !== "TH") {
		billTo += ds.countryName;
	}

	$('#BillTo').val(billTo);
	$('#billToModal').modal('hide');
}



$('#CardCode').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function() {
		var rs = $(this).val();
		var cust = rs.split(' | ');
		if(cust.length === 2) {
			let code = cust[0];
			let name = cust[1];
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
				$('#payment').val(ds.GroupNum);
				$('#priceList').val(ds.ListNum);
				$('#sale_id').val(ds.SlpCode).trigger('change');
				$('#sale_name').val(ds.sale_name);
			}
		}
	})
}



function editShipTo() {
	$('#shipToModal').modal('show');
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
				$('#s_address').val(ds.code);
				$('#sBlock').val(ds.address);
				$('#sStreet').val(ds.street);
				$('#sSubDistrict').val(ds.sub_district);
				$('#sDistrict').val(ds.district);
				$('#sProvince').val(ds.province);
				$('#sCountry').val(ds.country);
				$('#sPostCode').val(ds.postcode);

				let address = ds.address === "" ? "" : ds.address + " ";
				//let street = ds.street === "" ? "" : ds.street + " ";
				let sub_district = ds.sub_district === "" ? "" : ds.sub_district + " ";
				let district = ds.district === "" ? "" : ds.district + " ";
				let province = ds.province === "" ? "" : ds.province + " ";
				let postcode = ds.postcode === "" ? "" : ds.postcode + " "
				let country = ds.country === 'TH' ? '' : ds.countryName;
				let adr = address + sub_district + district + province + postcode + country;

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
				$('#b_address').val(ds.code);
				$('#bBlock').val(ds.address);
				$('#bStreet').val(ds.street);
				$('#bSubDistrict').val(ds.sub_district);
				$('#bDistrict').val(ds.district);
				$('#bProvince').val(ds.province);
				$('#bCountry').val(ds.country);
				$('#bPostCode').val(ds.postcode);

				let address = ds.address === "" ? "" : ds.address + " ";
				//let street = ds.street === "" ? "" : ds.street + " ";
				let sub_district = ds.sub_district === "" ? "" : ds.sub_district + " ";
				let district = ds.district === "" ? "" : ds.district + " ";
				let province = ds.province === "" ? "" : ds.province + " ";
				let postcode = ds.postcode === "" ? "" : ds.postcode + " "
				let country = ds.country === 'TH' ? '' : ds.countryName;
				let adr = address + sub_district + district + province + postcode + country;

				$('#BillTo').val(adr);
			}
		}
	})
}


function addRow() {
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
}

function removeRow() {
	$('.del-chk').each(function() {
		if($(this).is(':checked')) {
			var no = $(this).val();
			var is_free = $('#is-free-'+no).val();
			var rule_id = $('#rule-id-'+no).val();

			if(is_free) {
				var pno = $('#is-free-'+no).data('parentrow');
				el = $('#free-'+pno);
				qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
				picked = parseDefault(parseInt(el.data('picked')), 0);
				picked = picked - qty;

				if(picked >= 0) {
					freeQty = el.val();
					balance = freeQty - picked;
					el.data('picked', picked);
					$('#btn-free-'+pno).text("Free "+balance);
					$('#btn-free-'+pno).removeClass('hide');
				}
			}

			if(is_free == 0)
			{
				removeFreeRow();
			}

			$('#row-'+no).remove();
		}
	})

	recalTotal();
}


function removeFreeRow() {
	$('.free-row').remove();
	$('.free-item').remove();
	$('.free-btn').remove();
}


function updateDiscountRule(no) {
	let itemCode = $('#itemCode-'+no).val();
	let cardCode = $('#CardCode').val();
	let price = parseDefault(parseFloat($('#price-'+no).val()), 0);
	let qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
	let docDate = $('#DocDate').val();
	let payment = $('#payment').val();
	let channels = $('#channels').val();


	if(itemCode.length == 0) {
		return false;
	}

	if(qty <= 0) {
		return false;
	}

	setTimeout(function() {

		if(cardCode == "") {
			swal('กรุณาระบุลูกค้า');
			return false;
		}

		load_in();

		let uid = $('#free-item-'+no).data('uid');

		$('.is-free').each(function() {
			uuid = $(this).data('parent');
			if(uuid == uid) {
				rowNo = $(this).data('id');
				fqty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
				picked = parseDefault(parseInt($('#free-item-'+no).data('picked')), 0);
				picked = picked - fqty;
				picked = picked < 0 ? 0 : picked;
				$('#free-item-'+no).data('picked', picked);
				$('#row-'+rowNo).remove();
			}
		})

		$.ajax({
			url:HOME + "get_discount_data",
			type:"GET",
			cache:false,
			data:{
				'ItemCode' : itemCode,
				'CardCode' : cardCode,
				'Price' : price,
				'Qty' : qty,
				'DocDate' : docDate,
				'Payment' : payment,
				'Channels' : channels
			},
			success:function(rs) {
				load_out();
				var rs = $.trim(rs);
				if(isJson(rs)) {
					var ds = $.parseJSON(rs);
					var price = parseFloat(ds.Price);
					var stdPrice = parseFloat(ds.StdPrice);
					var sellPrice = parseDefault(parseFloat(ds.SellPrice), 0.00);
					var sysSellPrice = parseDefault(parseFloat(ds.sysSellPrice), 0.00);
					var lineTotal = parseFloat(ds.LineTotal);

					$('#product-id-'+no).val(ds.product_id);
					$('#price-'+no).val(price)
					$('#stdPrice-'+no).val(stdPrice);
					$('#sellPrice-'+no).val(sellPrice);
					$('#disc-amount-'+no).val(ds.discAmount);
					$('#line-disc-amount-'+no).val(ds.totalDiscAmount);
					$('#line-total-'+no).val(lineTotal);
					$('#vat-rate-'+no).val(ds.VatRate);
					$('#vat-amount-'+no).val(ds.VatAmount);
					$('#vat-total-'+no).val(ds.TotalVatAmount);
					$('#sys-disc-label-'+no).val(ds.sysDiscLabel);
					$('#disc-diff-'+no).val(0);
					$('#uom-code-'+no).val(ds.UomCode);
					$('#rule-id-'+no).val(ds.rule_id);
					$('#policy-id-'+no).val(ds.policy_id);
					$('#free-item-'+no).val(ds.freeQty);
					$('#free-item-'+no).data('rule', ds.rule_id);
					$('#disc-type-'+no).val(ds.discType);

					if(ds.freeQty > 0) {
						$('#free-item-'+no).data('uid', uniqueId());
						$('#btn-free-'+no).removeClass('hide');
					}

					$('#itemName-'+no).val(ds.ItemName);
					$('#uom-'+no).val(ds.UomName);
					$('#stdPrice-label-'+no).val(addCommas(stdPrice.toFixed(2)));
					$('#price-label-'+no).val(addCommas(price.toFixed(2)));
					$('#sysSellPrice-'+no).val(sysSellPrice);
					$('#disc-label-'+no).val(ds.discLabel);
					$('#vat-code-'+no).val(ds.VatGroup);
					$('#sell-price-'+no).val(sellPrice);
					$('#total-label-'+no).val(addCommas(lineTotal.toFixed(2)));

					//updateFreeItem(no);

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


function getItemData(no) {
	let itemCode = $('#itemCode-'+no).val();
	let cardCode = $('#CardCode').val();
	let priceList = $('#priceList').val();
	let docDate = $('#DocDate').val();
	let payment = $('#payment').val();
	let channels = $('#channels').val();
	let whs = $('#whs-'+no).val();
	let quotaNo = $('#quota-'+no).val();



	setTimeout(function() {

		if(cardCode == "") {
			swal('กรุณาระบุลูกค้า');
			return false;
		}


		load_in();

		let uid = $('#free-item-'+no).data('uid');

		$('.is-free').each(function() {
			uuid = $(this).data('parent');
			if(uuid == uid) {
				rowNo = $(this).data('id');
				fqty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
				picked = parseDefault(parseInt($('#free-item-'+no).data('picked')), 0);
				picked = picked - fqty;
				picked = picked < 0 ? 0 : picked;
				$('#free-item-'+no).data('picked', picked);
				$('#row-'+rowNo).remove();
			}
		})


		$.ajax({
			url:HOME + "get_item_data",
			type:"GET",
			cache:false,
			data:{
				'ItemCode' : itemCode,
				'CardCode' : cardCode,
				'PriceList' : priceList,
				'DocDate' : docDate,
				'Payment' : payment,
				'Channels' : channels,
				'whsCode' : whs,
				'quotaNo' : quotaNo
			},
			success:function(rs) {
				load_out();
				var rs = $.trim(rs);
				if(isJson(rs)) {
					var ds = $.parseJSON(rs);
					var price = parseFloat(ds.Price);
					var stdPrice = parseFloat(ds.StdPrice);
					var sellPrice = parseDefault(parseFloat(ds.SellPrice), 0.00);
					var lineTotal = parseFloat(ds.LineTotal);

					$('#product-id-'+no).val(ds.product_id);
					$('#price-'+no).val(price);
					$('#stdPrice-'+no).val(stdPrice);
					$('#sellPrice-'+no).val(sellPrice);
					$('#disc-amount-'+no).val(ds.discAmount);
					$('#line-disc-amount-'+no).val(ds.totalDiscAmount);
					$('#line-total-'+no).val(lineTotal);
					$('#vat-rate-'+no).val(ds.VatRate);
					$('#vat-amount-'+no).val(ds.VatAmount);
					$('#vat-total-'+no).val(ds.TotalVatAmount);
					$('#sys-disc-label-'+no).val(ds.sysDiscLabel);
					$('#uom-code-'+no).val(ds.UomCode);
					$('#rule-id-'+no).val(ds.rule_id);
					$('#policy-id-'+no).val(ds.policy_id);
					$('#free-item-'+no).val(ds.freeQty);
					$('#free-item-'+no).data('rule', ds.rule_id);
					$('#disc-type-'+no).val(ds.discType);

					if(ds.freeQty > 0) {
						$('#free-item-'+no).data('uid', uniqueId());
						$('#btn-free-'+no).removeClass('hide');
					}

					$('#itemName-'+no).val(ds.ItemName);
					$('#instock-'+no).val(ds.instock);
					$('#team-'+no).val(ds.team);
					$('#commit-'+no).val(ds.commit);
					$('#available-'+no).val(ds.available);
					$('#line-qty-'+no).val(ds.Qty);
					$('#uom-'+no).val(ds.UomName);
					$('#stdPrice-label-'+no).val(addCommas(stdPrice.toFixed(2)));
					$('#price-label-'+no).val(addCommas(price.toFixed(2)));
					$('#sysSellPrice-'+no).val(sellPrice);
					$('#disc-label-'+no).val(ds.discLabel);
					$('#vat-code-'+no).val(ds.VatGroup);
					$('#sell-price-'+no).val(sellPrice);
					$('#total-label-'+no).val(addCommas(lineTotal.toFixed(2)));
					$('#count-stock-'+no).val(ds.count_stock);
					$('#allow-change-discount-'+no).val(ds.allow_change_discount);

					$('#img-'+no).html('<img src="'+ds.image+'" width="40px;" height="40px;" />');

					if(ds.count_stock == '1') {
						$('#price-label-'+no).attr('disabled', 'disabled');
					}
					else {
						$('#price-label-'+no).removeAttr('disabled');
					}

					if(ds.allow_change_discount == '0') {
						$('#disc-label-'+no).attr('disabled', 'disabled');
					}
					else {
						$('#disc-label-'+no).removeAttr('disabled');
					}

					$('#line-qty-'+no).focus();

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



function getStock(no)  {
	let whsCode = $('#whs-'+no).val();
	let quota = $('#quota-'+no).val();
	let itemCode = $('#itemCode-'+no).val();

	$.ajax({
		url:HOME + 'get_stock',
		type:'GET',
		cache:false,
		data:{
			'itemCode' : itemCode,
			'whsCode' : whsCode,
			'quota' : quota
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				$('#instock-'+no).val(ds.OnHand);
				$('#team-'+no).val(ds.QuotaQty);
				$('#commit-'+no).val(ds.Committed);
				$('#available-'+no).val(ds.Available);
			}
		}
	});
}


function updateFreeItem() {
	let freeQty = 0;
	$('.free-item').each(function() {
		let qty = parseDefault(parseInt($(this).val()), 0);
		if(qty > 0) {
			freeQty += qty;
		}
	});

	if(freeQty == 0) {
		$('#free-badge').text("");
	}
	else {
		$('#free-badge').text(freeQty);
	}
}



function pickFreeItem(rule_id) {
	freeQty = $('#free-'+rule_id).val();
	uid = $('#free-'+rule_id).data('uid');
	picked = $('#free-'+rule_id).data('picked');

	if(rule_id != "" && rule_id > 0 && freeQty > 0 && picked < freeQty) {
		load_in();

		$.ajax({
			url:HOME + 'get_free_item',
			type:'GET',
			cache:false,
			data:{
				'rule_id' : rule_id,
				'freeQty' : freeQty,
				'picked' : picked,
				'uid' : uid
			},
			success:function(rs) {
				load_out();
				setTimeout(function() {
					$('#free-item-list').html(rs);
					$('.auto-select').focus(function() {
						$(this).select();
					});
					$('#free-item-modal').modal('show');

				}, 500)
			}
		});
	}
}


function addFreeRow(uuid) {
	let el = $('#input-'+uuid);
	let qty = parseDefault(parseInt(el.val()), );
	let product_id = el.data('item');
	let product_code = el.data('pdcode');
	let product_name = el.data('pdname');
	let parent_uid = el.data('parent');
	let rule_id = el.data('rule');
	let policy_id = el.data('policy');
	let img = el.data('img');
	let uom_code = el.data('uomcode');
	let uom_name = el.data('uom');
	let vat_code = el.data('vatcode');
	let vat_rate = el.data('vatrate');
	let price = el.data('price');
	let priceLabel = addCommas(price);
	let uid = uuid;
	let picked = 0;
	let freeQty = 0;
	let parent_row = "";

	$('.free-item').each(function() {
		if($(this).data('uid') == parent_uid) {
			parent_row = rule_id;
			freeQty = parseDefault(parseInt($(this).val()), 0);
			//picked = parseDefault(parseInt($(this).data('picked')), 0);
		}
	});


	$('.is-free').each(function() {
		if($(this).data('parent') == parent_uid) {
			let no = $(this).data('id');
			let pick = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
			picked += pick;
		}
	});

	picked = picked + qty;
	balance = freeQty - picked;

	if(balance >= 0) {
		$('#btn-free-'+rule_id).text("Free "+balance);
	}

	if(freeQty == picked) {
		$('#free-item-modal').modal('hide');
	}

	if(freeQty < picked) {
		$('#free-item-modal').modal('hide');
		swal("Error!", "จำนวนเกิน", "error");
		return false;
	}

	$('.item-code').each(function() {
		if($(this).val() == '') {
			no = $(this).data('id');
			$('#row-'+no).remove();
		}
	})

	if($('#'+uid).length) {
		let no = $('#'+uid).data('id');
		let cqty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
		let nqty = cqty + qty;
		$('#line-qty-'+no).val(nqty);

	}
	else {

		let no = $('#row-no').val();
		no++;

		$('#row-no').val(no);

		var data = {
			"no" : no,
			"uid" : uid,
			"parent_uid" : parent_uid,
			"parent_row" : parent_row,
			"product_id" : product_id,
			"product_code" : product_code,
			"product_name" : product_name,
			"qty" : qty,
			"price" : price,
			"priceLabel" : priceLabel,
			"sellPrice" : 0,
			"sysSellPrice" : 0,
			"discAmount" : price,
			"lineDiscAmount" : qty * price,
			"vat_code" : vat_code,
			"vat_rate" : vat_rate,
			"rule_id" : rule_id,
			"policy_id" : policy_id,
			"img" : img,
			"uom_code" : uom_code,
			"uom_name" : uom_name
		};

		var source = $('#free-row-template').html();
		var output = $('#details-template');

		render_append(source, data, output);
		init();
	}

	$('#free-' + parent_row).data('picked', picked);

	if(picked == freeQty) {
		$('#btn-free-' + parent_row).addClass('hide');
	}
}





function recalDiscount(no) {
	regex = /[^0-9+.]+/gi;

	label = $('#disc-label-'+no).val();
	label = label.replace(regex, '');

	first = label.charAt(0);
	last = label.charAt(label.length - 1);

	label = first == '+' ? label.slice(1) : label;
	label = last == '+' ? label.slice(0, -1) : label;

	$('#disc-label-'+no).val(label);

	price = parseDefault(parseFloat($('#price-'+no).val()), 0);
	price = roundNumber(price);

	sysSellPrice = parseDefault(parseFloat($('#sysSellPrice-'+no).val()), 0);

	if(price > 0) {

		disc = parseDiscount(label, price);

		discountAmount = disc.discountAmount;
		sellPrice = disc.sellPrice;
		discPrcnt = discountAmount > 0 ? (discountAmount/price) * 100 : 0.00;
		discPrcnt = roundNumber(discPrcnt);

		$('#totalDiscPercent-'+no).val(discPrcnt.toFixed(2));

		if( sysSellPrice > sellPrice ) {

			count_stock = $('#count-stock-'+no).val();
			if(count_stock == '1') {
				diff = sysSellPrice - sellPrice;

				percentDiff = (diff/sysSellPrice) * 100;
				percentDiff = roundNumber(percentDiff);
			}
			else {
				percentDiff = 0;
			}

			$('#disc-diff-'+no).val(percentDiff);
		}
		else {
			$('#disc-diff-'+no).val(0);
		}

		sellPrice = roundNumber(sellPrice, 4);


		$('#sellPrice-'+no).val(sellPrice);
		$('#sell-price-'+no).val(addCommas(sellPrice));

		recalAmount(no);
	}
}





function recalAmount(no) {
  currentInput = removeCommas($('#disc-label-'+no).val());
  val = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
	priceLabel = removeCommas($('#price-label-'+no).val());
	price = roundNumber(parseDefault(parseFloat(priceLabel), 0.00));

	$('#price-'+no).val(price);
	$('#price-label-'+no).val(addCommas(price));

	qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);

	disc = parseDiscount(val, price);

	discountAmount = disc.discountAmount;
	sellPrice = disc.sellPrice;
	sellPrice = roundNumber(sellPrice, 4);
	discPrcnt = discountAmount > 0 ? (discountAmount / price) * 100 : 0.00;

	$('#totalDiscPercent-'+no).val(discPrcnt.toFixed(2));

	if(sellPrice < 0 || sellPrice > price) {
		$('#disc-label-' + no).addClass('has-error');
		$('#disc-error-'+no).val(1);
		return false;
	}
	else {

		vat_rate = parseDefault(parseFloat($('#vat-rate-'+no).val()), 0) * 0.01;
		sysSellPrice = parseDefault(parseFloat($('#sysSellPrice-'+no).val()), 0.00);
		vatAmount = (sellPrice * vat_rate);

		vatTotal = (qty * vatAmount);
		vatTotal = roundNumber(vatTotal, 4);

		lineAmount = (qty * sellPrice);
		lineAmount = roundNumber(lineAmount, 2);

		lineDiscAmount = qty * discountAmount;
		lineDiscAmount = roundNumber(lineDiscAmount, 4);

		if( sysSellPrice > sellPrice ) {

			diff = roundNumber(sysSellPrice - sellPrice, 4);

			percentDiff = (diff/sysSellPrice) * 100;
			percentDiff = roundNumber(percentDiff, 2);

			$('#disc-diff-'+no).val(percentDiff);
		}
		else {
			$('#disc-diff-'+no).val(0);
		}

		$('#disc-error-'+no).val(0);
		$('#disc-label-' + no).removeClass('has-error');
		$('#disc-amount-'+no).val(discountAmount);
		$('#line-disc-amount-'+no).val(lineDiscAmount);
		$('#sellPrice-'+no).val(sellPrice);
		$('#sell-price-'+no).val(addCommas(sellPrice));
		$('#vat-amount-'+no).val(vatAmount);
		$('#vat-total-'+no).val(vatTotal);
		$('#line-total-'+no).val(lineAmount);
		$('#total-label-'+no).val(addCommas(lineAmount));

		recalTotal();

		removeFreeRow();
		$('#btn-save').addClass('hide');
		$('#btn-draft').addClass('hide');
	}
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
	var df_rate = parseDefault(parseFloat($('#vat_rate').val()), 7); //---- 7%
	var taxRate = df_rate * 0.01;
	var rounding = 0;

	$('.line-num').each(function(){
		var no = $(this).val();
		var qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);

		var price = parseDefault(parseFloat($('#price-'+no).val()), 0.00);
		price = roundNumber(price);

		var amount = parseDefault(parseFloat($('#line-total-'+no).val()), 0.00);
		amount = roundNumber(amount);

		var rate = parseDefault(parseFloat($('#vat-rate-'+no).val()), 0.00);

		if(qty > 0 && price > 0)
		{
			total += amount;

			if(rate > 0) {
				totalTaxAmount += amount;
			}
		}
	});

	//--- update bill discount
	var disc = parseDefault(parseFloat($('#discPrcnt').val()), 0);
	disc = roundNumber(disc, 2);

	var billDiscAmount = parseFloat(total * (disc * 0.01));
	billDiscAmount = roundNumber(billDiscAmount, 2);

	$('#discAmount').val(billDiscAmount);
	$('#discAmountLabel').val(addCommas(billDiscAmount));

	//---- bill discount amount
	amountAfterDisc = parseDefault(parseFloat(total - billDiscAmount), 0.00); //--- มูลค่าสินค้า หลังหักส่วนลด
	amountBeforeDiscWithTax = parseDefault(parseFloat(totalTaxAmount), 0.00); //-- มูลค่าสินค้า เฉพาะที่มีภาษี
	//--- คำนวนภาษี หากมีส่วนลดท้ายบิล
	//--- เฉลี่ยส่วนลดออกให้ทุกรายการ โดยเอาส่วนลดท้ายบิล(จำนวนเงิน)/มูลค่าสินค้าก่อนส่วนลด
	//--- ได้มูลค่าส่วนลดท้ายบิลที่เฉลี่ยนแล้ว ต่อ บาท เช่น หารกันมาแล้ว ได้ 0.16 หมายถึงทุกๆ 1 บาท จะลดราคา 0.16 บาท
	everageBillDisc = parseFloat((total > 0 ? billDiscAmount/total : 0));
	//everageBillDisc = roundNumber(everageBillDisc, 2); //-- ไม่ต้องปัดเศษ

	//--- นำผลลัพธ์ข้างบนมาคูณ กับ มูลค่าที่ต้องคิดภาษี (ตัวที่ไม่มีภาษีไม่เอามาคำนวณ)
	//--- จะได้มูลค่าส่วนลดที่ต้องไปลบออกจากมูลค่าสินค้าที่ต้องคิดภาษี
	totalDiscTax = roundNumber(amountBeforeDiscWithTax * everageBillDisc, 2);


	amountToPayTax = roundNumber(amountBeforeDiscWithTax - totalDiscTax, 2);

	taxAmount = roundNumber(amountToPayTax * taxRate, 2);

	docTotal = roundNumber(amountAfterDisc + taxAmount + rounding, 2);

	$('#totalAmount').val(total);
	$('#totalAmountLabel').val(addCommas(total.toFixed(2)));
	$('#tax').val(taxAmount);
	$('#taxLabel').val(addCommas(taxAmount.toFixed(2)));
	$('#docTotal').val(docTotal);
	$('#docTotalLabel').val(addCommas(docTotal.toFixed(2)));
}





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




function init() {

	$('.item-code').autocomplete({
		source:BASE_URL + 'auto_complete/get_item_code_and_name',
		autoFocus:true,
		open:function(event){
			var $ul = $(this).autocomplete('widget');
			$ul.css('width', 'auto');
		},
		close:function(){
			var data = $(this).val();
			var arr = data.split(' | ');
			if(arr.length == 3) {
				let no = $(this).data("id");
				let id = arr[0];
				let code = arr[1];
				let name = arr[2];

				$(this).val(code);
				getItemData(no);
			}
			else {
				$(this).val('');
			}
		}
	});



	$('.item-name').keyup(function(e) {
		if(e.keyCode == 13) {
			no = $(this).data("id");
			getItemData(no);
		}
	});



	$('.line-qty').change(function() {
		let no = $(this).data('id');
		updateDiscountRule(no);

		setTimeout(function() {
			no++;
			no++;
			if($('#itemCode-'+no).length && $('#itemCode-'+no).val() == "") {
				$('#itemCode-'+no).focus();
			}
			else {
				count = 0;
				$('.item-code').each(function() {
					if($(this).val() == '') {
						no = $(this).data('id');
						count++;
						$('#itemCode-'+no).focus();
						return true;
					}
				});

				if(count == 0) {
					no = addRow();
					$('#itemCode-'+no).focus();
				}
			}
		}, 200)
	});



	$('.line-qty').focus(function() {
		$(this).select();
	});

} //-- end init




function nextFocus(name, el) {
	var no = getNo(el);
	$('#'+name+'-'+no).focus();
}

$(document).ready(function(){
	init();
})




$('.autosize').autosize({append: "\n"});


function duplicateSO(code) {
	swal({
    title:'Duplicate Sales Order ',
    text:'ต้องการสร้างใบสั่งขายใหม่ เหมือนใบสั่งขายนี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'Cancle',
    confirmButtonText:'Duplicate',
		closeOnConfirm:true
  },
  function(){
		load_in();
		$.ajax({
			url:HOME + 'duplicate_sales_order',
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
						setTimeout(function() {
							swal({
								title:'Success',
								text: 'Duplicate Sales Order success : '+ds.code,
								type:'success',
								timer:1000
							});

							setTimeout(function(){
								goEdit(ds.code);
							},1200)

						}, 500);

					}
					else {
						swal({
							title:"Error!",
							text:ds.error,
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


function recal_all_discount() {
	let count = 0;
	let no = [];
	$('.item-code').each(function() {
		if($(this).val() != '') {
			count++;
			no.push($(this).data('id'));
		}
	});

	if(count > 0) {
		swal({
			title:'Warning',
			text:'เนื่องจากมีการเปลี่ยนแปลงข้อมูลสำคัญที่มีผลต่อส่วนลด แนะนำให้ทำการคำนวณส่วนลดใหม่ ต้องการคำนวณส่วนลดใหม่หรือไม่ ?',
			type:'warning',
			showCancelButton:true,
			cancelButtonText:'ไม่ต้อง',
			confirmButtonText:'คำนวณส่วนลดใหม่',
			closeOnConfirm:true
		},
		function() {
			recal_order_discount(no);
		});
	}
}



function recal_order_discount(no_arr) {
	console.log(no_arr);
	var p = $.when();

	no_arr.forEach(function(no, key) {
		let code = $('#itemCode-'+no).val();
		let is_free = $('#is-free-'+no).val();

		if(code.length && is_free == 0) {

			p = p.then(updateDiscountRule(no));
		}
	});
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
