function goBack() {
	window.location.href = HOME;
}


function addNew() {
	window.location.href = HOME + "add_new";
}


function getEdit(id) {
	window.location.href = HOME + "edit/"+id;
}


function viewDetail(id) {
	window.location.href = HOME + "view_detail/"+id;
}


function toggleAproveDoc(docType) {
	if($('#approve-'+docType).is(':checked')) {
		$('#min-disc-'+docType).removeAttr('disabled');
		$('#max-disc-'+docType).removeAttr('disabled');
		$('#min-amount-'+docType).removeAttr('disabled');
		$('#max-amount-'+docType).removeAttr('disabled');
		$('#min-disc-'+docType).focus();
	}
	else {
		$('#min-disc-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
		$('#max-disc-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
		$('#min-amount-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
		$('#max-amount-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
	}
}


$('.disc').focus(function() {
	$(this).select();
});

$('.disc').change(function() {
	let val = parseDefault(parseFloat($(this).val()), 0);
	if(val > 100) {
		$(this).val('100.00');
	}
	else if(val < 0) {
		$(this).val('0.00');
	}
	else {
		$(this).val(val.toFixed(2));
	}
})

$('.amount').focus(function() {
	$(this).select();
});

$('.amount').change(function() {
	let val = $(this).val();
	let am = parseDefault(parseFloat(removeCommas(val)), 0.00);
	let as = am.toFixed(2);
	$(this).val(addCommas(as));
});


function saveAdd() {
	const user_id = $('#user').val();
	let status = $('#status').is(':checked') ? 1 : 0;
	var error = 0;
	let approve = 0;
	let review = 0;
	let data = [];

	if(user_id == "") {
		set_error($('#user'), $('#user-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#user'), $('#user-error'));
	}

	$('.docType').each(function() {
		let code = $(this).val();
		let is_review = $('#review-'+code).is(':checked') ? 1 : 0;
		let is_approve = $('#approve-'+code).is(':checked') ? 1 : 0;
		let minDisc = parseDefault(parseFloat($('#min-disc-'+code).val()), 0);
		let maxDisc = parseDefault(parseFloat($('#max-disc-'+code).val()), 0);
		let minAmount = parseDefault(parseFloat(removeCommas($('#min-amount-'+code).val())), 0);
		let maxAmount = parseDefault(parseFloat(removeCommas($('#max-amount-'+code).val())), 0);

		if(is_approve) {
			if(minDisc < 0 || minDisc > maxDisc) {
				$('#min-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#min-disc-'+code).removeClass('has-error');
			}

			if(maxDisc > 0 && maxDisc < minDisc) {
				$('#max-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-disc-'+code).removeClass('has-error');
			}

			if(minAmount < 0 || minAmount > maxAmount) {
				$('#min-amount-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#min-amount-'+code).removeClass('has-error');
			}

			if(maxAmount <= 0 || maxAmount < minAmount) {
				$('#max-amount-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-amount-'+code).removeClass('has-error');
			}

			approve++;
		}

		if(is_review) {
			review++;
		}
		
		let row = {
			"docType" : code,
			"review" : is_review,
			"approve" : is_approve,
			"minDisc" : minDisc,
			"maxDisc" : maxDisc,
			"minAmount" : minAmount,
			"maxAmount" : maxAmount
		}

		data.push(row);
	});


	if(approve == 0 && review == 0) {
		swal("กรุณาระบุข้อมูล Approval");
		return false;
	}

	if(error > 0) {
		swal("Approval ไม่ถูกต้อง");
		return false;
	}



	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'user_id' : user_id,
			'approval' : JSON.stringify(data),
			'status' : status
		},
		success:function(rs) {
			load_out();
			rs = $.trim(rs);

			if(rs === 'success') {
				swal({
					title:'Success',
					text:"เพิ่มผู้อนุมัติเรียบร้อยแล้ว สามารถเพิ่มผู้อนุมัติคนใหม่ได้ทันที",
					type:'success'
				}, function() {
					setTimeout(function() {
						addNew()
					}, 100);
				});
			}
			else {
				swal({
					title:'Error!',
					text: rs,
					type:'error'
				});
			}
		}
	});
}



function update() {
	const approver_id = $('#id').val();
	let status = $('#status').is(':checked') ? 1 : 0;
	var error = 0;
	let approve = 0;
	let review = 0;
	let data = [];

	$('.docType').each(function() {
		let code = $(this).val();
		let is_review = $('#review-'+code).is(':checked') ? 1 : 0;
		let is_approve = $('#approve-'+code).is(':checked') ? 1 : 0;
		let minDisc = parseDefault(parseFloat($('#min-disc-'+code).val()), 0);
		let maxDisc = parseDefault(parseFloat($('#max-disc-'+code).val()), 0);
		let minAmount = parseDefault(parseFloat(removeCommas($('#min-amount-'+code).val())), 0);
		let maxAmount = parseDefault(parseFloat(removeCommas($('#max-amount-'+code).val())), 0);

		if(is_approve) {
			if(minDisc < 0 || minDisc > maxDisc) {
				$('#min-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#min-disc-'+code).removeClass('has-error');
			}

			if(maxDisc > 0 && maxDisc < minDisc) {
				$('#max-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-disc-'+code).removeClass('has-error');
			}

			if(minAmount < 0 || minAmount > maxAmount) {
				$('#min-amount-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#min-amount-'+code).removeClass('has-error');
			}

			if(maxAmount <= 0 || maxAmount < minAmount) {
				$('#max-amount-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-amount-'+code).removeClass('has-error');
			}

			approve++;
		}

		if(is_review) {
			review++;
		}

		let row = {
			"docType" : code,
			"review" : is_review,
			"approve" : is_approve,
			"minDisc" : minDisc,
			"maxDisc" : maxDisc,
			"minAmount" : minAmount,
			"maxAmount" : maxAmount
		}

		data.push(row);
	});


	if(approve == 0 && review == 0) {
		swal("กรุณาระบุข้อมูล Approval");
		return false;
	}

	if(error > 0) {
		swal("Approval ไม่ถูกต้อง");
		return false;
	}



	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : approver_id,
			'approval' : JSON.stringify(data),
			'status' : status
		},
		success:function(rs) {
			load_out();
			rs = $.trim(rs);

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				swal({
					title:'Error!',
					text: rs,
					type:'error'
				});
			}
		}
	});
}



function getDelete(id, code) {
	swal({
		title:'คุณแน่ใจ ?',
		text:'ต้องการลบ '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonColor:'#DD6B55',
		confirmButtonText:'ใช่, ฉันต้องการลบ',
		cancelButtonText:'ยกเลิก',
		closeOnConfirm:false
	}, function() {
			$.ajax({
				url:HOME + 'delete',
				type:'POST',
				cache:false,
				data:{
					'id' : id
				},
				success:function(rs) {
					if(rs === 'success') {
						swal({
							title:'Deleted',
							type:'success',
							timer:1000
						});

						setTimeout(function() {
							goBack();
						}, 1500);
					}
					else {
						swal({
							title:'Error!',
							text: rs,
							type:'error'
						});
					}
				}
			});
	});
}


function clearFilter() {
	$.get(HOME + "clear_filter", function() {
		goBack();
	})
}
