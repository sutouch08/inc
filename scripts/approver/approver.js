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


function saveAdd() {
	const user_id = $('#user').val();
	const uname = $('#user option:selected').text();
	var error = 0;
	var team = [];
	var brand = [];

	if(user_id == "") {
		set_error($('#user'), $('#user-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#user'), $('#user-error'));
	}

	$('.chk-team').each(function() {
		if($(this).is(':checked')) {
			team.push($(this).val());
		}
	});

	if(team.length == 0) {
		swal("Please select Sales Team");
		return false;
	}

	$('.chk-brand').each(function() {
		if($(this).is(':checked')) {
			id = $(this).val();
			percent = parseDefault(parseFloat($('#brand-disc-'+id).val()), 0.00);
			if(percent <= 0.00) {
				error++;
				$('#brand-disc-'+id).addClass('has-error');
			}
			else {
				$('#brand-disc-'+id).removeClass('has-error');
			}

			row = {"id" : id, "max_disc" : percent};
			brand.push(row);
		}
	});

	if(brand.length == 0) {
		swal("Please select Brand");
		return false;
	}

	if(error > 0) {
		swal("Max Disc must be greater than 0");
		return false;
	}

	const status = $('#status').is(':checked') ? 1 : 0;

	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'user_id' : user_id,
			'uname' : uname,
			'team' : team,
			'brand' : brand,
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

				setTimeout(function() {
					addNew()
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
}



function update() {
	const approver_id = $('#id').val();
	const user_id = $('#user').val();
	const uname = $('#user option:selected').text();
	var error = 0;
	var team = [];
	var brand = [];

	if(user_id == "") {
		set_error($('#user'), $('#user-error'), "Required!");
		return false;
	}
	else {
		clear_error($('#user'), $('#user-error'));
	}

	$('.chk-team').each(function() {
		if($(this).is(':checked')) {
			team.push($(this).val());
		}
	});

	if(team.length == 0) {
		swal("Please select Sales Team");
		return false;
	}

	$('.chk-brand').each(function() {
		if($(this).is(':checked')) {
			id = $(this).val();
			percent = parseDefault(parseFloat($('#brand-disc-'+id).val()), 0.00);
			if(percent <= 0.00) {
				error++;
				$('#brand-disc-'+id).addClass('has-error');
			}
			else {
				$('#brand-disc-'+id).removeClass('has-error');
			}

			row = {"id" : id, "max_disc" : percent};
			brand.push(row);
		}
	});

	if(brand.length == 0) {
		swal("Please select Brand");
		return false;
	}

	if(error > 0) {
		swal("Max Disc must be greater than 0");
		return false;
	}

	const status = $('#status').is(':checked') ? 1 : 0;

	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : approver_id,
			'user_id' : user_id,
			'uname' : uname,
			'team' : team,
			'brand' : brand,
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


function getSearch() {
	$('#searchForm').submit();
}

function clearFilter() {
	$.get(HOME + "clear_filter", function() {
		goBack();
	})
}
