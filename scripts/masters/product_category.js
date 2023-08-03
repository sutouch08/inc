var HOME = BASE_URL + 'masters/product_category/';

function addNew() {
  window.location.href = HOME + 'add_new';
}



function goBack() {
  window.location.href = HOME;
}



function getEdit(id) {
  window.location.href = HOME + 'edit/'+id;
}


function viewDetail(id) {
	window.location.href = HOME + 'view_detail/'+id;
}

function update_sap() {
	var id = $('#id').val();
	setTimeout(function() {
		load_in();

		$.ajax({
			url:HOME + 'send_to_sap/'+id,
			type:'POST',
			cache:false,
			success:function(rs) {
				load_out();
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
						text:rs,
						type:'error'
					});
				}
			}
		});
	}, 200);
}



function create_sap() {
	var id = $('#id').val();
	setTimeout(function() {
		load_in();

		$.ajax({
			url:HOME + 'create_sap/'+id,
			type:'POST',
			cache:false,
			success:function(rs) {
				load_out();
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
						text:rs,
						type:'error'
					});
				}
			}
		});
	}, 200);
}


function save() {
	let code = $('#code').val();
	let name = $('#name').val();
	let parent = $('input[name=tabs]:checked').val();

	parent = parent === undefined ? 0 : parent;

	if(code.length === 0) {
		set_error($('#code'), $('#code-error'), "Required");
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	if(name.length === 0) {
		set_error($('#name'), $('#name-error'), "Required");
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'code' : code,
			'name' : name,
			'parent_id' : parent
		},
		success:function(rs) {
			load_out();
			var rs = $.trim(rs);

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
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}



function update() {
	let id = $('#id').val();
	let name = $('#name').val();
	let parent = $('input[name=tabs]:checked').val();

	parent = parent === undefined ? 0 : parent;

	if(name.length === 0) {
		set_error($('#name'), $('#name-error'), "Required");
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'name' : name,
			'parent_id' : parent
		},
		success:function(rs) {
			load_out();
			var rs = $.trim(rs);

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					window.location.reload();
				}, 1200)
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}




function setActive(el) {
	let id = el.data('id');
	let active = el.is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'set_active/'+id+'/'+active,
		type:'GET',
		cache:false,
		success:function(rs) {

		}
	})
}


function clearFilter(){
  goBack();
}


function getDelete(code, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ ' + name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    swal({
			title:'Deleted',
			type:'success',
			timer:1000
		})
  })
}



function getSearch(){
  goBack();
}



function toggleTree(id) {

	let ul = $('#catchild-'+id);

	if(ul.hasClass('hide')) {
		ul.removeClass('hide');
		$('#catbox-'+id).removeClass('fa-plus-square-o');
		$('#catbox-'+id).addClass('fa-minus-square-o');
	}
	else {
		ul.addClass('hide');
		$('#catbox-'+id).removeClass('fa-minus-square-o');
		$('#catbox-'+id).addClass('fa-plus-square-o');
	}
}
