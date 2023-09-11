function addNew(){
  window.location.href = HOME + 'add_new';
}



function goBack(){
  window.location.href = HOME;
}


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
				url:BASE_URL + 'orders/quotation/approve',
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


function confirmReject() {
	let code = $('#code').val();
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

	if(reason.length < 5) {
		$('#reject-error').text('กรุณาระบุเหตุผลอย่างน้อย 5 ตัวอักษร');
		return false;
	}
	else {
		$('#reject-error').text('');
	}

	$('#rejectModal').modal('hide');

	load_in();

	setTimeout(() => {
		$.ajax({
			url:BASE_URL + "orders/quotation/reject",
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



function viewDetail(code){
  window.location.href = HOME + 'view_detail/'+code;
}



$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#toDate").datepicker("option", "minDate", ds);
	}
});

$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fromDate").datepicker("option", "maxDate", ds);
	}
});
