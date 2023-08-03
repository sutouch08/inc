function goBack(){
  window.location.href = HOME;
}


function addNew() {
	window.location.href = HOME + 'add_new';
}


// function saveAdd() {
// 	let code = $('#code').val();
// 	let name = $('#name').val();
// 	let position = $('#position').val();
// 	let active = $('#active').is(':checked') ? 1 : 0;
//
// 	if(code.length == 0) {
// 		set_error($('#code'), $('#code-error'), "Required");
// 		return false;
// 	}
// 	else {
// 		clear_error($('#code'), $('#code-error'));
// 	}
//
// 	if(name.length == 0) {
// 		set_error($('#name'), $('#name-error'), "Required");
// 		return false;
// 	}
// 	else {
// 		clear_error($('#name'), $('#name-error'));
// 	}
//
// 	$.ajax({
// 		url:HOME + 'add',
// 		type:'POST',
// 		cache:false,
// 		data:{
// 			'code' : code,
// 			'name' : name,
// 			'position' : position,
// 			'active' : active
// 		},
// 		success:function(rs) {
// 			if(rs === 'success') {
// 				swal({
// 					title:'Success',
// 					type:'success',
// 					timer:1000
// 				});
//
// 				setTimeout(function() {
// 					addNew();
// 				}, 1200);
// 			}
// 			else {
// 				swal({
// 					title:'Error!',
// 					text: rs,
// 					type:'error'
// 				})
// 			}
// 		}
// 	})
// }


function syncData(){
	load_in();

	$.ajax({
		url:HOME + 'sync_data',
		type:'GET',
		cache:false,
		success:function(rs) {
			load_out();

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					goBack();
				}, 1200);
			}
			else {
				swal({
					titl:"Error!",
					text: rs,
					type:'error'
				})
			}
		}
	})
}


function getEdit(id){
  window.location.href = HOME + 'edit/'+id;
}


function saveAdd() {
	const code = $.trim($('#code').val());
	const name = $.trim($('#name').val());
	const pos = $('#position').val();
	const active = $('#active').is(':checked') ? 1 : 0;
	const is_default = $('#is_default').is(':checked') ? 1 : 0;

	if(code.length == 0) {
		set_error($('#code'), $('#code-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#code'), $('#code-error'));
	}

	if(name.length == 0) {
		set_error($('#name'), $('#name-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	$.ajax({
		url:HOME + 'is_exists_code',
		type:'POST',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			if(rs === 'exists') {
				set_error($('#code'), $('#code-error'), code + " already exists.");
				return false;
			}
			else {
				$.ajax({
					url:HOME + 'is_exists_name',
					type:'POST',
					cache:false,
					data:{
						'name' : name
					},
					success:function(rs) {
						if(rs == 'exists') {
							set_error($('#name'), $('#name-error'), name + " already exists.");
							return false;
						}
						else {
							$.ajax({
								url:HOME + 'add',
								type:'POST',
								cache:false,
								data:{
									'code' : code,
									'name' : name,
									'position' : pos,
									'active' : active,
									'is_default' : is_default
								},
								success:function(rs) {
									if(rs === 'success') {
										swal({
											title:'Success',
											type:'success',
											timer:1000
										});

										setTimeout(function() {
											addNew();
										}, 1500);
									}
									else {
										swal({
											title:"Error!",
											text: rs,
											type:"error"
										});
									}
								}
							});
						}
					}
				})
			}
		}
	});
}



function update() {
	const id = $('#id').val();
	const name = $.trim($('#name').val());
	const pos = $('#position').val();
	const active = $('#active').is(':checked') ? 1 : 0;
	const is_default = $('#is_default').is(':checked') ? 1 : 0;

	if(name.length == 0) {
		set_error($('#name'), $('#name-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	$.ajax({
		url:HOME + 'is_exists_name',
		type:'POST',
		cache:false,
		data:{
			'name' : name,
			'id' : id
		},
		success:function(rs) {
			if(rs === 'exists') {
				set_error($('#name'), $('#name-error'), name + " already exists.");
				return false;
			}
			else {
				$.ajax({
					url:HOME + 'update',
					type:'POST',
					cache:false,
					data:{
						'id' : id,
						'name' : name,
						'position' : pos,
						'active' : active,
						'is_default' : is_default
					},
					success:function(rs) {
						if(rs === 'success') {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});
						}
						else {
							swal({
								title:"Error!",
								text: rs,
								type:"error"
							});
						}
					}
				});
			}
		}
	});
}


function getDelete(id, name){
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการลบ ' + name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    $.ajax({
			url:HOME + 'delete',
			type:'POST',
			cache:false,
			data:{
				"id" : id
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
