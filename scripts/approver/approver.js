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
		//$('#review-'+docType).prop('checked', false);
		$('#max-disc-'+docType).removeAttr('disabled').focus();
		$('#max-amount-'+docType).removeAttr('disabled');
	}
	else {
		$('#max-disc-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
		$('#max-amount-'+docType).val('0.00').attr('disabled', 'disabled').removeClass('has-error');
	}
}


function toggleReview(docType) {
	// if($('#review-'+docType).is(':checked')) {
	// 	$('#approve-'+docType).prop('checked', false);
	// 	toggleAproveDoc(docType);
	// }
}


$('.disc').focus(function() {
	$(this).select();
});

$('.amount').focus(function() {
	$(this).select();
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
		let disc = parseDefault(parseFloat($('#max-disc-'+code).val()), 0);
		let amount = parseDefault(parseFloat($('#max-amount-'+code).val()), 0);

		if(is_approve) {
			if(disc == 0) {
				$('#max-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-disc-'+code).removeClass('has-error');
			}

			if(amount == 0) {
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
			"maxDisc" : disc,
			"maxAmount" : amount
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
		let disc = parseDefault(parseFloat($('#max-disc-'+code).val()), 0);
		let amount = parseDefault(parseFloat($('#max-amount-'+code).val()), 0);

		if(is_approve) {
			if(disc == 0) {
				$('#max-disc-'+code).addClass('has-error');
				error++;
			}
			else {
				$('#max-disc-'+code).removeClass('has-error');
			}

			if(amount == 0) {
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
			"maxDisc" : disc,
			"maxAmount" : amount
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
