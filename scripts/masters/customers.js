function goBack(){
  window.location.href = HOME;
}


function getEdit(id){
  window.location.href = HOME + 'edit/'+id;
}


function viewDetail(id) {
	window.location.href = HOME + 'view_detail/'+id;
}

function update() {
	let id = $('#id').val();
	let type = $('#TypeCode').val();
	let grade = $('#GradeCode').val();
	let region = $('#RegionCode').val();
	let area = $('#AreaCode').val();

	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'TypeCode' : type,
			'GradeCode' : grade,
			'RegionCode' : region,
			'AreaCode' : area
		},
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
					text: rs,
					type:'error'
				});
			}
		}
	});

}

function getSearch(){
  $('#searchForm').submit();
}
