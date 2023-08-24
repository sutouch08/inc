function goBack() {
  window.location.href = HOME;
}


function addNew() {
	$('#imageModal').modal('show');
}

function add()
{
	var emp_id = $('#emp_id').val();
	var image_data	= $("#img-data").val();

  if(emp_id == '') {
    swal('Oops!', 'Please choose employee', 'warning');
    return false;
  }

	if( image_data == '' ){
		swal('Oops!', 'Unable to read attached image data. Please attach the file again.', 'error');
		return false;
	}


	$("#imageModal").modal('hide');

	load_in();

	$.ajax({
		url: HOME + 'add',
		type:"POST",
		cache: "false",
		data: {
      "emp_id" : emp_id,
      "image_data" : image_data
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success')
			{
				swal({
					title : 'Success',
					type: 'success',
					timer: 1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);

			}
			else
			{
				swal("ข้อผิดพลาด", rs, "error");
			}
		},
		error:function(xhr, status, error) {
			load_out();
			swal({
				title:'Error!',
				text:"Error-"+xhr.status+": "+xhr.statusText,
				type:'error'
			})
		}
	});
}


function readURL(input)
{
	 if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#previewImg').html('<img id="previewImage" src="'+e.target.result+'" style="max-width:200px;" alt="รูปสินค้า" />');
          $('#img-data').val(e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
		}
}


$("#image").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;
		if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
		{
			swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
			$(this).val('');
			return false;
		}

		if( size > 2000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error");
			$(this).val('');
			return false;
		}

		readURL(this);

		$("#btn-select-file").css("display", "none");
		$("#block-image").animate({opacity:1}, 1000);
	}
});


function removeFile()
{
	$("#previewImg").html('');
	$("#block-image").css("opacity","0");
	$("#btn-select-file").css('display', '');
	$("#image").val('');
  $('#img-data').val('');
}


function showSignature(emp_id) {
  let img_data = $('#image-data-'+emp_id).val();

  $('#view-image').html('<img id="previewImage" src="'+img_data+'" style="max-width:200px;" alt="รูปสินค้า" />');

  $('#viewModal').modal('show');
}


function getEdit(emp_id) {
  let img_data = $('#image-data-'+emp_id).val();
  $('#previewImg').html('<img id="previewImage" src="'+img_data+'" style="max-width:200px;" alt="รูปสินค้า" />');
  $('#emp_id').val(emp_id).trigger('change');
  $('#image-data').val(img_data);

  $("#btn-select-file").css("display", "none");
  $("#block-image").animate({opacity:1}, 1000);

  $('#imageModal').modal('show');
}


function confirmDelete(id, name) {
	swal({
    title:'Are sure ?',
    text:'ต้องการลบ '+ name +' หรือไม่ ?',
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
			data: {
				'emp_id' : id
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
						text:rs,
						type:'error'
					});
				}
			}
		});
  });
}
