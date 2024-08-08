function addNew(){
  window.location.href = HOME + 'add_new';
}



function goBack(){
  window.location.href = HOME;
}




function goEdit(code) {
  let uuid = get_uuid();
  $.ajax({
    url:HOME + 'is_document_avalible',
    type:'GET',
    cache:false,
    data:{
      'code' : code,
      'uuid' : uuid
    },
    success:function(rs) {
      if(rs === 'available') {
        window.location.href = HOME + 'edit/'+code+'/'+uuid;
      }
      else {
        swal({
          title:'Oops!',
          text:'เอกสารกำลังถูกเปิด/แก้ไข โดยเครื่องอื่นอยู่ ไม่สามารถแก้ไขได้ในขณะนี้',
          type:'warning'
        });
      }
    }
  });
}


function viewDetail(code){
  window.location.href = HOME + 'view_detail/'+code;
}


function cancleOrder(code){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิก "+code+" หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการยกเลิก',
		cancelButtonText: 'ไม่ใช่',
		closeOnConfirm: true
		},
		function(){
			load_in();
			$.ajax({
				url:HOME + 'cancle_order',
				type:'POST',
				cache:false,
				data:{
					'code' : code
				},
				success:function(rs) {
					load_out();

					if(rs == 'success') {
						setTimeout(function(rs) {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});

							setTimeout(function() {
								window.location.reload();
							}, 1200);
						}, 500);
					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								type:'error',
								text:rs
							});
						}, 500);
					}
				}
			});
	});
}


function cancleSap(code) {
	swal({
    title:'ยกเลิกเอกสารบน SAP',
    text:'คุณต้องการยกเลิกเอกสารนี้บน SAP หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
		confirmButtonColor: "#DD6B55",
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ใช่ ต้องการยกเลิก',
		closeOnConfirm:true
  },
  function(){
		load_in();
		$.ajax({
			url:HOME + 'cancle_sap_order',
			type:'POST',
			cache:false,
			data: {
				'code' : code
			},
			success:function(rs) {
				load_out();
				if(rs == 'success') {
					setTimeout(function() {
						swal({
							title:'Success',
							type:'success',
							timer:1000
						});

						setTimeout(function() {
							window.location.reload();
						}, 1200);
					}, 500)
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



function sendToSap(code) {

  load_in();

  $.ajax({
    url:HOME + 'send_to_sap',
    type:'POST',
    cache:false,
    data:{
      'code' : code
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

function leave(){
  swal({
    title:'คุณแน่ใจ ?',
    text:'รายการทั้งหมดจะไม่ถูกบันทึก ต้องการออกหรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ออกจากหน้านี้',
  },
  function(){
    goBack();
  });
}



function showMessage(code) {
	$.ajax({
		url:HOME + 'get_order_message',
		type:'GET',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let source = $('#failed-template').html();
				let output = $('#failed-table');

				render(source, ds, output);

				$('#failedModal').modal('show');
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

function showReason(code) {
	$.ajax({
		url:HOME + 'get_reject_message',
		type:'GET',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let source = $('#reason-template').html();
				let output = $('#reason');

				render(source, ds, output);

				$('#reasonModal').modal('show');
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

$('#DocDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(ds) {
    $('#ShipDate').datepicker("option", "minDate", ds);
  }
});

$('#ShipDate').datepicker({
  dateFormat:'dd-mm-yy'
});


$('#TextDate').datepicker({
  dateFormat:'dd-mm-yy'
});

//--- load sq modal
$("#fDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#tDate").datepicker("option", "minDate", ds);
	}
});

$("#tDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fDate").datepicker("option", "maxDate", ds);
	}
});
