var rejectMode = "";

function createSO(code) {
	swal({
    title:'Create Sale Order',
    text:'ต้องการสร้าง Sale Order ใหม่ จาก Sale Quotation นี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ใช่ ฉันต้องการ',
		closeOnConfirm:true
  },
  function() {
		window.location.href = BASE_URL + 'orders/orders/create_from_sq/'+code;
  });
}



function printSQ(code) {
	var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 800)/2;
	var code  = $("#code").val();
  var target  = HOME + 'print_sq/'+code;
  window.open(target, '_blank', prop);
}


// function reject() {
// 	let code = $('#code').val();
// 	let url = rejectMode == 'approve' ?
// 	swal({
// 		title:'Reject !',
// 		text:'ต้องการ Reject '+code+' หรือไม่ ?',
// 		type:'warning',
// 		showCancelButton:true,
// 		confirmButtonColor:'#d15b47',
// 		confirmButtonText:'Yes',
// 		cancelButtonText:'No',
// 		closeOnConfirm:true
// 	}, function() {
// 		load_in();
// 		setTimeout(() => {
// 			$.ajax({
// 				url:HOME + 'reject',
// 				type:'POST',
// 				cache:false,
// 				data:{
// 					'code' : code
// 				},
// 				success:function(rs) {
// 					load_out();
//
// 					if(rs == 'success') {
// 						swal({
// 							title:'Success',
// 							type:'success',
// 							timer:1000
// 						});
//
// 						setTimeout(() => {
// 							window.location.reload();
// 						}, 1200);
// 					}
// 					else {
// 						swal({
// 							title:'Error!',
// 							text:rs,
// 							type:'error'
// 						});
// 					}
// 				},
// 				error:function(xhr, textStatus) {
// 					swal({
// 						title:'Error !',
// 						text: "Request failed : "+textStatus,
// 						type:'error'
// 					});
// 				}
// 			})
// 		}, 200);
// 	})
// }

function approve() {
	let code = $('#code').val();
	swal({
		title:'Approval',
		text:'ต้องการอนุมัติ '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, function() {
		load_in();
		setTimeout(() => {
			$.ajax({
				url:HOME + 'approve',
				type:'POST',
				cache:false,
				data:{
					'code' : code
				},
				success:function(rs) {
					load_out();

					if(isJson(rs)) {

						let ds = JSON.parse(rs);
						if(ds.status == 'success') {

							if(ds.ex == 1) {
								swal({
									title:'Oops',
									text:ds.message,
									type:'warning'
								}, function() {
									setTimeout(() => {
										window.location.reload();
									}, 200);
								});
							}
							else {
								swal({
									title:'Success',
									type:'success',
									timer:1000
								});

								setTimeout(() => {
									window.location.reload();
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
				},
				error:function(xhr, textStatus) {
					swal({
						title:'Error !',
						text: "Request failed : "+textStatus,
						type:'error'
					});
				}
			})
		}, 200);
	})
}


function confirmReject(option) {
	let code = $('#code').val();
	rejectMode = option;

	swal({
		title:'Reject !',
		text:'ต้องการ Reject '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonColor:'#d15b47',
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, function() {

		setTimeout(() => {
			$('#reject-reason').val('');
	    $('#rejectModal').modal('show');
	    $('#rejectModal').on('shown.bs.modal', function() {
	      $('#reject-reason').focus();
	    });
		}, 200);
	})
}

function reject() {
	let code = $('#code').val();
	let reason = $('#reject-reason').val();
	let url = 'reject';

	if(reason.length < 5) {
		$('#reject-error').text('กรุณาระบุเหตุผลอย่างน้อย 5 ตัวอักษร');
		return false;
	}
	else {
		$('#reject-error').text('');
	}

	if(rejectMode != "") {
		url = rejectMode == 'approve' ? 'reject' : 'reject_review';
	}
	else {
		$('#reject-error').text('Error : Reject mode is not defined');
		return false;
	}

	$('#rejectModal').modal('hide');

	load_in();

	setTimeout(() => {
		$.ajax({
			url:HOME + url,
			type:'POST',
			cache:false,
			data:{
				'code' : code,
				'reason' : reason
			},
			success:function(rs) {
				load_out();

				if(rs == 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					setTimeout(() => {
						window.location.reload();
					}, 1200);
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					});
				}
			},
			error:function(xhr, textStatus) {
				swal({
					title:'Error !',
					text: "Request failed : "+textStatus,
					type:'error'
				});
			}
		})
	}, 200);
}


function confirmReview() {
	let code = $('#code').val();
	swal({
		title:'Confirm',
		text:'ต้องการยืนยัน '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, function() {
		load_in();
		setTimeout(() => {
			$.ajax({
				url:HOME + 'confirm_review',
				type:'POST',
				cache:false,
				data:{
					'code' : code
				},
				success:function(rs) {
					load_out();

					if(isJson(rs)) {

						let ds = JSON.parse(rs);
						if(ds.status == 'success') {

							if(ds.ex == 1) {
								swal({
									title:'Oops',
									text:ds.message,
									type:'warning'
								}, function() {
									setTimeout(() => {
										window.location.reload();
									}, 200);
								});
							}
							else {
								swal({
									title:'Success',
									type:'success',
									timer:1000
								});

								setTimeout(() => {
									window.location.reload();
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
				},
				error:function(xhr, textStatus) {
					swal({
						title:'Error !',
						text: "Request failed : "+textStatus,
						type:'error'
					});
				}
			})
		}, 200);
	})
}
