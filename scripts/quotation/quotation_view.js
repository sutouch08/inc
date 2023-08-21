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
  function(){
		load_in();
		$.ajax({
			url: BASE_URL + 'orders/orders/create_from_sq',
			type:'POST',
			cache:false,
			data:{
				'sq_code' : code
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
								text: 'Sale Order Created Successfull : ' + ds.code,
								type:'success',
								timer:1000
							});

							setTimeout(function(){
								window.location.href = BASE_URL + 'orders/orders/edit/'+ds.code;
							},1200)
						}, 500)

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



function printSQ(code) {
	var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 800)/2;
	var code  = $("#code").val();
  var target  = HOME + 'print_sq/'+code;
  window.open(target, '_blank', prop);
}


function reject() {
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
		load_in();
		setTimeout(() => {
			$.ajax({
				url:HOME + 'reject',
				type:'POST',
				cache:false,
				data:{
					'code' : code
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
	})
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


function rejectReview() {
	let code = $('#code').val();
	swal({
		title:'Reject !',
		text:'ต้องการตีกลับ '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonColor:'#d15b47',
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		closeOnConfirm:true
	}, function() {
		load_in();
		setTimeout(() => {
			$.ajax({
				url:HOME + 'reject_review',
				type:'POST',
				cache:false,
				data:{
					'code' : code
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
	})
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
