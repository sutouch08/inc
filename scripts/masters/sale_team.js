function goBack(){
  window.location.href = HOME;
}

function addNew(){
	window.location.href = HOME + 'add_new';
}

function getEdit(id){
  window.location.href = HOME + 'edit/'+id;
}

function save() {
	let code = $('#code').val();
	let name = $('#name').val();

	if(code.length === 0) {
		set_error($('#code'), $('#code-error'), 'Required');
		return false;
	}
	else {
		clear_error($('#code'), $('#code-error'));
	}

	if(name.length === 0) {
		set_error($('#name'), $('#name-error'), 'Required');
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'code' : code,
			'name' : name
		},
		success:function(rs) {
			if(rs == 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					addNew();
				}, 1200);
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
}


function update() {

	let id = $('#id').val();
	let name = $('#name').val();

	if(name.length === 0) {
		set_error($('#name'), $('#name-error'), 'Required');
		return false;
	}
	else {
		clear_error($('#name'), $('#name-error'));
	}

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'name' : name
		},
		success:function(rs) {
			if(rs == 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				set_error($('#name'), $('#name-error'), rs);
				return false;
			}
		}
	})
}


function getDelete(id, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ '+name+' หรือไม่ ?',
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
				'id' : id
			},
			success:function(rs) {
				if(rs === 'success') {
					swal({
						title:'Deleted',
						type:'success',
						timer:1000
					});

					setTimeout(goBack(), 1500);
				}
				else {
					swal({
						title:"Error!",
						type:"error",
						text:rs
					});
				}
			}
		})
  })
}


function clearFilter() {
	$.get(BASE_URL + 'masters/sales_team/clear_filter', function() {
		goBack();
	})
}


function getSearch(){
  $('#searchForm').submit();
}
